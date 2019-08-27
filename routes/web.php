<?php

use App\DeliveryStatus;
use App\Driver;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {


    //$delivery = \App\Delivery::find(1);
    //\App\Jobs\ProcessSendMail::dispatch($delivery);
    //return \App\Http\Resources\DeliveryStatusResource::collection(\App\DeliveryStatus::paginate(15));
    //return \App\Http\Resources\DeliveryResource::collection(\App\Delivery::paginate(15));
    //dd(0);


    //$driver = new \App\MailDrivers\Mailjet\Driver(env("MJ_APIKEY_PUBLIC"), env("MJ_APIKEY_PRIVATE"));
    //$driver = new \App\MailDrivers\SendGrid\Driver(env("SENDGRID_API_KEY"));
    //dd($driver->send($delivery));



    /*//dd(json_encode((new \App\Mail\EmailAddress("x@c.com", "jjj"))->jsonSerialize()));

    $mail = \App\Mail::find(1);
    $delivery = \App\Delivery::find(1);
    //dd($mail->deliveries);
    //$driver = new \App\MailDrivers\SendGrid\Driver(env("SENDGRID_API_KEY"));
    $driver = new \App\MailDrivers\Mailjet\Driver(env("MJ_APIKEY_PUBLIC"), env("MJ_APIKEY_PRIVATE"));
    //dd($mail->toEmailAddresses->all());
    //print(json_encode($driver->jsonHelperMail($delivery)));exit;
    dd($driver->send($delivery));*/

    return view('welcome');
});
