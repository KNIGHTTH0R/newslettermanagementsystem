<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Delivery;
use App\DeliveryStatus;

Route::prefix('webhook')->group(function () {
    Route::post('sendgrid', function () {
        $payload = json_decode(request()->getContent(), true);
        $driver = \App\Driver::whereName("SendGrid")->first();
        foreach ($payload as $item) {
            $messageId = explode('.', $item['sg_message_id'])[0];
            $status = ucfirst($item['event']);

            $delivery = Delivery::whereMessageId($messageId)->first();

            if ($delivery) {
                $deliveryStatus = new DeliveryStatus();
                $deliveryStatus->status = $status;
                $deliveryStatus->details = json_encode($item);
                $deliveryStatus->driverId = $driver->id;
                $deliveryStatus->deliveryId = $delivery->id;
                $deliveryStatus->save();

                if (in_array($status, ['Bounce', 'Bounced', 'Dropped'])) {
                    //If the mail service fails to deliver the mail, then we try another mail API if exists.
                    \App\Jobs\ProcessSendMail::dispatch($delivery);
                }
            }
        }
    });

    Route::post('mailjet', function () {
        $payload = json_decode(request()->getContent(), true);
        $driver = \App\Driver::whereName("Mailjet")->first();
        foreach ($payload as $item) {
            $messageId = substr($item['MessageID'], 0, 14);
            $status = ucfirst($item['event']);
            if ($status == 'Sent')
                $status = 'Delivered';

            $delivery = Delivery::whereMessageId($messageId)->first();

            if ($delivery) {
                $deliveryStatus = new DeliveryStatus();
                $deliveryStatus->status = $status;
                $deliveryStatus->details = json_encode($item);
                $deliveryStatus->driverId = $driver->id;
                $deliveryStatus->deliveryId = $delivery->id;
                $deliveryStatus->save();

                if (in_array($status, ['Bounce', 'Blocked'])) {
                    //If the mail service fails to deliver the mail, then we try another mail API if exists.
                    \App\Jobs\ProcessSendMail::dispatch($delivery);
                }
            }
        }
    });
});

// List deliveries
Route::get('deliveries', 'APIController@getDeliveries');

// List single delivery
Route::get('delivery/{id}', 'APIController@getDelivery');

// List statuses of an delivery
Route::get('delivery/{id}/status', 'APIController@getDeliveryStatuses');

// List attachments of an mail
Route::get('mail/{id}/attachment', 'APIController@getMailAttachments');

// Create new mail
Route::post('mail', 'APIController@createMail');
