<?php
namespace App\Http\Controllers;
use App\Models\Notification;
use Illuminate\Http\Request; // Import Request
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request) // Inject Request here
    {
        // --- CRUCIAL ADDITION: Check if the request expects JSON ---
        if (!$request->expectsJson()) {
            // If it's a regular browser request (not expecting JSON),
            // return a 404 Not Found error.
            abort(404);
        }
        // --- END CRUCIAL ADDITION ---

        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning('Attempted to fetch notifications for unauthenticated user.');
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $notifications = Notification::where('user_id', $user->id)
                ->with('actor.profile')
                ->latest()
                ->take(20) // Adjust limit as needed for API
                ->get()
                ->map(function ($n) {
                    $actorProfileImage = ($n->actor && $n->actor->profile)
                                            ? ($n->actor->profile->image
                                                ? asset('storage/' . $n->actor->profile->image)
                                                : asset('images/default-profile.png'))
                                            : asset('images/default-profile.png');
                    return [
                        'id' => $n->id,
                        'actor_id' => $n->actor_id,
                        'type' => $n->type,
                        'message' => $n->message,
                        'subject_id' => $n->subject_id,
                        'subject_type' => $n->subject_type,
                        'read' => (bool) $n->read,
                        'profile_image' => $actorProfileImage,
                        'created_at' => $n->created_at ? $n->created_at->toISOString() : null,
                    ];
                });

            return response()->json($notifications); // This is what's sent for AJAX requests
        } catch (\Exception $e) {
            Log::error('Error fetching custom notifications for user ' . (Auth::id() ?? 'N/A') . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['error' => 'Failed to retrieve notifications.'], 500);
        }
    }

  
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
             return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            Notification::where('user_id', $user->id)
                ->whereIn('id', $request->ids)
                ->update(['read' => true]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error marking custom notifications as read for user ' . (Auth::id() ?? 'N/A') . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['error' => 'Failed to mark notifications as read.'], 500);
        }
    }
}