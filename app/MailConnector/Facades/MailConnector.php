<?php


namespace App\MailConnector\Facades;

use App\MailConnector\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\MailConnector\Contracts\Provider driver(string $driver = null)
 * @see \App\MailConnector\MailConnectorManager
 */
class MailConnector extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
