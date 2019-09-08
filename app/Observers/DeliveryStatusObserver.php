<?php

namespace App\Observers;

use App\DeliveryStatus;
use App\Events\WebsocketDeliveryStatusChangeEvent;

class DeliveryStatusObserver
{
    /**
     * Handle the delivery status "created" event.
     *
     * @param  \App\DeliveryStatus  $deliveryStatus
     * @return void
     */
    public function created(DeliveryStatus $deliveryStatus)
    {
        broadcast(new WebsocketDeliveryStatusChangeEvent($deliveryStatus));
    }

    /**
     * Handle the delivery status "updated" event.
     *
     * @param  \App\DeliveryStatus  $deliveryStatus
     * @return void
     */
    public function updated(DeliveryStatus $deliveryStatus)
    {
        //
    }

    /**
     * Handle the delivery status "deleted" event.
     *
     * @param  \App\DeliveryStatus  $deliveryStatus
     * @return void
     */
    public function deleted(DeliveryStatus $deliveryStatus)
    {
        //
    }

    /**
     * Handle the delivery status "restored" event.
     *
     * @param  \App\DeliveryStatus  $deliveryStatus
     * @return void
     */
    public function restored(DeliveryStatus $deliveryStatus)
    {
        //
    }

    /**
     * Handle the delivery status "force deleted" event.
     *
     * @param  \App\DeliveryStatus  $deliveryStatus
     * @return void
     */
    public function forceDeleted(DeliveryStatus $deliveryStatus)
    {
        //
    }
}
