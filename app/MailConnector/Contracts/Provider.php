<?php


namespace App\MailConnector\Contracts;


use App\Delivery;

interface Provider
{
    /**
     * Sends the mail.
     *
     * @param Delivery $delivery
     * @return array
     */
    public function send(Delivery $delivery);

    /**
     * Return an array representing a Mail object for API
     *
     * @param Delivery $delivery
     * @return array
     */
    public function delivery2array(Delivery $delivery);
}
