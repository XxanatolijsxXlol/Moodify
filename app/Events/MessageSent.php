<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon; // Make sure to import Carbon

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $name,
        public string $text,
        public int $conversationId,
        public int $userId,
        public ?string $profileImage,
        // Add these two properties for real-time ID matching and status
        public int $id, // The actual database ID of the message
        public ?string $temp_id = null // The temporary client-side ID
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('conversation.' . $this->conversationId);
    }

    public function broadcastWith(): array
    {
        // When broadcasting, we want to send the initial status and timestamps
        // This is important for the client to render the correct checkmark immediately.
        return [
            'id' => $this->id,
            'temp_id' => $this->temp_id, // Include temp_id for optimistic UI removal
            'name' => $this->name,
            'text' => $this->text,
            'user_id' => $this->userId,
            'profile_image' => $this->profileImage,
            'status' => 'sent', // New messages are always 'sent' initially
            'delivered_at' => null, // Not delivered yet
            'read_at' => null, // Not read yet
 'created_at' => Carbon::now()->toISOString(), // Send current time in ISO 8601 UTC format
        ];
    }
}