<?php

namespace App\Events;

use App\Models\Notification; // Import the Notification model
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Assuming this event is meant to be broadcast for real-time notifications
class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification; // Make the property public so it can be serialized for broadcasting

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Notification  $notification // Type-hint the Notification model
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Assuming you want to broadcast to a private channel for the recipient user
        // The recipient user's ID is stored in the notification's user_id column
        return [
            new PrivateChannel('notifications.' . $this->notification->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        // Define a name for the event when broadcasting
        return 'new-notification';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // Define what data from the notification model should be broadcast
        // This data will be available on the frontend via event.detail
        return [
            'id' => $this->notification->id,
            'actor_id' => $this->notification->actor_id,
            'type' => $this->notification->type,
            'message' => $this->notification->message,
            'subject_id' => $this->notification->subject_id,
            'subject_type' => $this->notification->subject_type,
            'read' => (bool) $this->notification->read, // Ensure boolean
            'profile_image' => $this->notification->profile_image,
       

            'created_at' => $this->notification->created_at ? $this->notification->created_at->toISOString() : null,
            // Add any other data the frontend needs from the notification
        ];
    }
}
