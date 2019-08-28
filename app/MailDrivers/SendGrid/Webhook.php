<?php


namespace App\MailDrivers\SendGrid;


use App\Delivery;
use App\DeliveryStatus;

class Webhook
{
    /**
     * @param $payload
     */
    public static function handle($payload)
    {
        $driver = \App\Driver::whereName("SendGrid")->first();
        foreach ($payload as $item) {
            $message_id = explode('.', $item['sg_message_id'])[0];
            $status = ucfirst($item['event']);

            $delivery = Delivery::whereMessageId($message_id)->first();

            if ($delivery) {
                $delivery_status = new DeliveryStatus();
                $delivery_status->status = $status;
                $delivery_status->details = json_encode($item);
                $delivery_status->driver_id = $driver->id;
                $delivery_status->delivery_id = $delivery->id;
                $delivery_status->save();

                if (in_array($status, ['Bounce', 'Bounced', 'Dropped'])) {
                    //If the mail service fails to deliver the mail, then we try another mail API if exists.
                    \App\Jobs\ProcessSendMail::dispatch($delivery);
                }
            }
        }
    }
}
