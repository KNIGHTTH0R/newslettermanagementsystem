<?php

namespace App\Jobs;

use App\Delivery;
use App\DeliveryStatus;
use App\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

            $driver_path = $d->path;
            $driver = new $driver_path();
            $response = $driver->send($this->delivery);
            Log::info(json_encode($response));

            $delivery_status = new DeliveryStatus();
            $delivery_status->status = $response['status'];
            $delivery_status->details = json_encode($response);
            $delivery_status->driver_id = $d->id;
            $delivery_status->delivery_id = $this->delivery->id;
            $delivery_status->save();

            if ($response['status'] == 'Sent') {
                $this->delivery->message_id = $response['message_id'];
                $this->delivery->save();
                break;
            }

        }

    }
}
