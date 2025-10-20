<?php

namespace App\Events;

use App\Models\Base\Supply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplyUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Supply $supply;
    public $operationType;
    /**
     * Create a new event instance.
     */
    public function __construct( Supply $supply, $operationType)
    {
        $this->supply = $supply;
        $this->operationType = $operationType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
