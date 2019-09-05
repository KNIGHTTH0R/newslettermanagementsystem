<?php

namespace App\Observers;

use App\Delivery;
use App\Jobs\ProcessSendMail;

class DeliveryObserver
{
    /**
     * Handle the delivery "created" event.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function created(Delivery $delivery)
    {
        ProcessSendMail::dispatch($delivery);
    }

    /**
     * Handle the delivery "updated" event.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function updated(Delivery $delivery)
    {
        //
    }

    /**
     * Handle the delivery "deleted" event.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function deleted(Delivery $delivery)
    {
        //
    }

    /**
     * Handle the delivery "restored" event.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function restored(Delivery $delivery)
    {
        //
    }

    /**
     * Handle the delivery "force deleted" event.
     *
     * @param Delivery $delivery
     * @return void
     */
    public function forceDeleted(Delivery $delivery)
    {
        //
    }
}
