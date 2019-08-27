<?php


namespace App\MailDrivers\Mailjet;


use App\Delivery;
use App\DeliveryStatus;

class Webhook
{
    /**
     * @param $payload
     */
    public static function handle($payload)
    {
        $driver = \App\Driver::whereName("Mailjet")->first();
        foreach ($payload as $item) {
            $message_id = $item['Message_GUID'];
            $status = ucfirst($item['event']);
            if ($status == 'Sent')
                $status = 'Delivered';

            $delivery = Delivery::whereMessageId($message_id)->first();

            if ($delivery) {
                $delivery_status = new DeliveryStatus();
                $delivery_status->status = $status;
                $delivery_status->details = json_encode($item);
                $delivery_status->driver_id = $driver->id;
                $delivery_status->delivery_id = $delivery->id;
                $delivery_status->save();

                if (in_array($status, ['Bounce', 'Blocked'])) {
                    //If the mail service fails to deliver the mail, then we try another mail API if exists.
                    \App\Jobs\ProcessSendMail::dispatch($delivery);
                }
            }
        }
    }
}
