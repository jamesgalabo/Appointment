<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $type, // 'appointment' or 'reservation'
        public int $id,
        public string $status,
        public ?int $studentId = null,
        public ?int $houseId = null
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('kidaboard-public'),
            new Channel('kidaboard-user.' . ($this->studentId ?? 0)),
        ];
    }

    public function broadcastAs(): string
    {
        return 'booking.updated';
    }
}
