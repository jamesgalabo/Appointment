<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $messageData;
    public int $recipientId;

    public function __construct(Message $message, int $recipientId)
    {
        $this->recipientId = $recipientId;
        $message->loadMissing(['sender', 'conversation.boardingHouse']);

        $this->messageData = [
            'id'                  => $message->id,
            'conversation_id'     => $message->conversation_id,
            'sender_id'           => $message->sender_id,
            'sender_name'         => $message->sender->name,
            'sender_role'         => $message->sender->role,
            'body'                => $message->body,
            'is_read'             => $message->is_read,
            'created_at'          => $message->created_at->toIso8601String(),
            'formatted_time'      => $message->created_at->format('h:i A'),
            'boarding_house_name' => $message->conversation->boardingHouse?->name,
            'boarding_house_id'   => $message->conversation->boarding_house_id,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('kidaboard-conversation.' . $this->messageData['conversation_id']),
            new Channel('kidaboard-user.' . $this->recipientId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return $this->messageData;
    }
}
