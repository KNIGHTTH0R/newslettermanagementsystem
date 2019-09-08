<?php

namespace App\Jobs;

use App\Delivery;
use App\DeliveryStatus;
use App\Driver;
use App\MailConnector\Facades\MailConnector;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Delivery
     */
    protected $delivery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Driver::orderBy('priority', 'ASC')->get() as $d) {

            // If we already tried to send the mail using this driver, then we skip it.
            if ($this->delivery->statuses()->where('driver_id', '=', $d->id)->exists())
                continue;

            $driver = MailConnector::driver($d->name);
            $response = $driver->send($this->delivery);
            Log::info(json_encode($response));

            $delivery_status = new DeliveryStatus();
            $delivery_status->status = $response['status'];
            $delivery_status->details = json_encode($response);
            $delivery_status->driverId = $d->id;
            $delivery_status->deliveryId = $this->delivery->id;
            $delivery_status->save();

            if ($response['status'] == 'Sent') {
                $this->delivery->message_id = $response['message_id'];
                $this->delivery->save();
                break;
            }

        }

    }
}
