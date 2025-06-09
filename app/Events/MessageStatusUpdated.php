<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $messageId;
    public $conversationId;
    public $status;
    public $deliveredAt;
    public $readAt;

    public function __construct(int $messageId, int $conversationId, string $status, ?string $deliveredAt = null, ?string $readAt = null)
    {
        $this->messageId = $messageId;
        $this->conversationId = $conversationId;
        $this->status = $status;
        $this->deliveredAt = $deliveredAt;
        $this->readAt = $readAt;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('conversation.' . $this->conversationId);
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'status' => $this->status,
            'delivered_at' => $this->deliveredAt,
            'read_at' => $this->readAt,
        ];
    }
}