<?php


namespace App\MailConnector\Contracts;


interface Factory
{
    /**
     * Get an provider implementation.
     *
     * @param  string  $driver
     * @return \App\MailConnector\Contracts\Provider
     */
    public function driver($driver = null);
}
