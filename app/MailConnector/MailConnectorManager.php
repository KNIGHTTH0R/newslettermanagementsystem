<?php


namespace App\MailConnector;

use App\MailConnector\Connectors\AbstractProvider;
use App\MailConnector\Connectors\MailjetProvider;
use App\MailConnector\Connectors\SendGridProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class MailConnectorManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param string $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Build an provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $config['endpoint'],
            $config['prikey'],
            $config['pubkey'],
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return AbstractProvider
     */
    protected function createSendGridDriver()
    {
        $config = $this->app['config']['services.sendgrid'];
        return $this->buildProvider(
            SendGridProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return AbstractProvider
     */
    protected function createMailjetDriver()
    {
        $config = $this->app['config']['services.mailjet'];
        return $this->buildProvider(
            MailjetProvider::class, $config
        );
    }

    /**
     * Get the default driver name.
     *
     * @return string
     * @throws \InvalidArgumentException
     *
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Mail Connector driver was specified.');
    }
}
