<?php

namespace App\Events;

use App\DeliveryStatus;
use App\Http\Resources\DeliveryStatusResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebsocketDeliveryStatusChangeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var DeliveryStatusResource
     */
    public $newDeliveryStatus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DeliveryStatus $deliveryStatus)
    {
        $this->newDeliveryStatus = DeliveryStatusResource::make($deliveryStatus);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('DeliveryChannel');
    }
}
