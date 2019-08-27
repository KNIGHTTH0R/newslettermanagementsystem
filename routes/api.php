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

Route::prefix('webhook')->group(function () {
    Route::post('sendgrid', function () {
        $payload = json_decode(request()->getContent(), true);
        \App\MailDrivers\SendGrid\Webhook::handle($payload);
    });

    Route::post('mailjet', function () {
        $payload = json_decode(request()->getContent(), true);
        \App\MailDrivers\Mailjet\Webhook::handle($payload);
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
