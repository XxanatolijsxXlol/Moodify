<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\MessageStatusUpdated; // Import the new event
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Import Carbon for timestamps

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Get all conversations involving the authenticated user
        $conversations = Conversation::where('user1_id', $user->id)
                                    ->orWhere('user2_id', $user->id)
                                    ->with(['user1.profile', 'user2.profile']) // Eager load user profiles
                                    ->get();

        // Create a unique list of users involved in conversations (excluding self)
        $chatUsers = collect();
        foreach ($conversations as $conversation) {
            $otherUser = ($conversation->user1_id === $user->id) ? $conversation->user2 : $conversation->user1;
            // Add conversation ID to the otherUser object for easy access in the view
            $otherUser->conversation_id = $conversation->id;
            $chatUsers->put($otherUser->id, $otherUser); // Use put to ensure uniqueness by user ID
        }

        // Sort by the latest message in the conversation (optional, but good for chat lists)
        // This requires fetching the latest message for each conversation or adding a 'last_message_at' to conversation
        // For simplicity, we'll just return the unique users for now.
        // If you need sorting by last message, consider adding a 'last_message_at' column to the conversations table
        // and updating it on message send.

        return view('messages', [
            'chatUsers' => $chatUsers->values(), // Pass the collection of users
            'conversation' => null, // No specific conversation selected initially
            'messages' => collect() // No messages initially
        ]);
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        if ($user->id !== $conversation->user1_id && $user->id !== $conversation->user2_id) {
            abort(403, 'Unauthorized');
        }

        // Get all conversations involving the authenticated user (for the left panel)
        $allConversations = Conversation::where('user1_id', $user->id)
                                    ->orWhere('user2_id', $user->id)
                                    ->with(['user1.profile', 'user2.profile']) // Eager load user profiles
                                    ->get();

        $chatUsers = collect();
        foreach ($allConversations as $conv) {
            $otherUser = ($conv->user1_id === $user->id) ? $conv->user2 : $conv->user1;
            $otherUser->conversation_id = $conv->id; // Attach conversation ID
            $chatUsers->put($otherUser->id, $otherUser);
        }


        // Eager load profile for the user who sent the message
        $messages = $conversation->messages()->with(['user' => fn($q) => $q->select('id', 'name')->with('profile')])
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return view('messages', [
            'chatUsers' => $chatUsers->values(),
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function start(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $recipientId = $request->recipient_id;

        if ($user->id === (int)$recipientId) {
            return redirect()->route('messages.index')->with('error', 'You cannot chat with yourself.');
        }

        $conversation = Conversation::where(function ($query) use ($user, $recipientId) {
            $query->where('user1_id', $user->id)->where('user2_id', $recipientId);
        })->orWhere(function ($query) use ($user, $recipientId) {
            $query->where('user1_id', $recipientId)->where('user2_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => min($user->id, $recipientId),
                'user2_id' => max($user->id, $recipientId),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function send(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'conversation_id' => 'required|exists:conversations,id',
            'temp_id' => 'nullable|string', // Ensure temp_id is validated
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        if ($user->id !== $conversation->user1_id && $user->id !== $conversation->user2_id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        try {
            $message = Message::create([
                'name' => $user->name,
                'text' => $request->text,
                'user_id' => $user->id,
                'conversation_id' => $conversation->id,
                'status' => 'sent', // Initial status
            ]);

            // Create notification for the other user
            $recipientId = $conversation->user1_id === $user->id ? $conversation->user2_id : $conversation->user1_id;
            if ($recipientId !== $user->id) {
                $notification = Notification::create([
                    'user_id' => $recipientId,
                    'actor_id' => $user->id,
                    'type' => 'message',
                    'subject_id' => $conversation->id,
                    'subject_type' => Conversation::class,
                    'message' => "{$user->name} sent you a message.",
                    'read' => false,
                ]);

                NotificationEvent::dispatch($notification);
            }

            // Dispatch MessageSent event with actual message ID and temp_id
            MessageSent::dispatch(
                $user->name,
                $request->text,
                $conversation->id,
                $user->id,
                $user->profile->image ? asset('storage/' . $user->profile->image) : asset('storage/profile_images/default-profile.png'),
                $message->id, // Pass actual message ID
                $request->temp_id // Pass the client-side temp_id
            );

            return response()->json([
                'success' => true,
                'message' => $message,
                'temp_id' => $request->temp_id // Return temp_id for client-side matching if needed
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'error' => 'Failed to send message'], 500);
        }
    }

 public function markAsDelivered(Request $request, Message $message)
    {
        $user = Auth::user();
        if ($user->id !== $message->conversation->user1_id && $user->id !== $message->conversation->user2_id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        if ($message->user_id !== $user->id && $message->status === 'sent') { // Ensure it's not sender and status is 'sent'
            $message->status = 'delivered';
            $message->delivered_at = Carbon::now();
            $message->save();

            MessageStatusUpdated::dispatch(
                $message->id,
                $message->conversation_id,
                $message->status,
                $message->delivered_at->toDateTimeString(),
                $message->read_at?->toDateTimeString()
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Message not eligible for delivery status update'], 400);
    }

    // This method handles marking ALL unread messages in a conversation as read (e.g., on page load)
    public function markAsRead(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        if ($user->id !== $conversation->user1_id && $user->id !== $conversation->user2_id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $otherUserId = ($conversation->user1_id === $user->id) ? $conversation->user2_id : $conversation->user1_id;

        $unreadMessages = $conversation->messages()
            ->where('user_id', $otherUserId) // Messages sent by the other person
            ->whereNull('read_at')           // Not yet marked as read
            ->get();

        $updatedCount = 0;
        foreach ($unreadMessages as $message) {
            $message->status = 'read';
            $message->delivered_at = $message->delivered_at ?? Carbon::now(); // Ensure delivered_at is set if not already
            $message->read_at = Carbon::now();
            $message->save();
            $updatedCount++;

            MessageStatusUpdated::dispatch(
                $message->id,
                $message->conversation_id,
                $message->status,
                $message->delivered_at->toDateTimeString(),
                $message->read_at->toDateTimeString()
            );
        }

        return response()->json(['success' => true, 'count' => $updatedCount]);
    }

    /**
     * Mark specific messages within a conversation as read.
     * This is used for real-time updates when recipient receives a new message.
     */
    public function markSpecificMessagesAsRead(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        $user = Auth::user();
        if ($user->id !== $conversation->user1_id && $user->id !== $conversation->user2_id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $messageIdsToMark = $request->message_ids;
        $otherUserId = ($conversation->user1_id === $user->id) ? $conversation->user2_id : $conversation->user1_id;

        // Fetch messages that belong to this conversation, are sent by the other user,
        // are in the provided list of IDs, and are not yet read.
        $messagesToUpdate = $conversation->messages()
            ->whereIn('id', $messageIdsToMark)
            ->where('user_id', $otherUserId)
            ->whereNull('read_at')
            ->get();

        $updatedCount = 0;
        foreach ($messagesToUpdate as $message) {
            $message->status = 'read';
            $message->delivered_at = $message->delivered_at ?? Carbon::now(); // Ensure delivered_at is set
            $message->read_at = Carbon::now();
            $message->save();
            $updatedCount++;

            // Broadcast status update for each updated message
            MessageStatusUpdated::dispatch(
                $message->id,
                $message->conversation_id,
                $message->status,
                $message->delivered_at->toDateTimeString(),
                $message->read_at->toDateTimeString()
            );
        }

        return response()->json(['success' => true, 'count' => $updatedCount]);
    }
}