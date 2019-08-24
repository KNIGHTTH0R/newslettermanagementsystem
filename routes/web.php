<?php

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

    //dd(json_encode((new \App\Mail\EmailAddress("x@c.com", "jjj"))->jsonSerialize()));

    $mail = \App\Mail::find(1);
    $driver = new \App\MailDrivers\SendGrid\Driver(env("SENDGRID_API_KEY"));
    //dd($mail->toEmailAddresses->all());
    dd($driver->send($mail));

    return view('welcome');
});
