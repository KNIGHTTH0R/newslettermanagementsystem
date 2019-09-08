<?php


namespace App\MailConnector\Connectors;

use App\Delivery;
use GuzzleHttp\Client;
use App\MailConnector\Contracts\Provider as ProviderContract;

abstract class AbstractProvider implements ProviderContract
{
    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;
    /**
     * The private key.
     *
     * @var string
     */
    protected $privateKey;
    /**
     * The public key.
     *
     * @var string
     */
    protected $publicKey;
    /**
     * The endpoint URL.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * The custom Guzzle configuration options.
     *
     * @var array
     */
    protected $guzzle = [];

    /**
     * Create a new provider instance.
     *
     * @param string $endpoint
     * @param string $privateKey
     * @param string|null $publicKey
     * @param array $guzzle
     * @return void
     */
    public function __construct($endpoint, $privateKey, $publicKey = null, $guzzle = [])
    {
        $this->guzzle = $guzzle;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->endpoint = $endpoint;
    }

    /**
     * Sends the mail.
     *
     * @param Delivery $delivery
     * @return array
     */
    abstract public function send(Delivery $delivery);

    /**
     * Return an array representing a Mail object for API
     *
     * @param Delivery $delivery
     * @return array
     */
    abstract public function delivery2array(Delivery $delivery);

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle);
        }
        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param \GuzzleHttp\Client $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }
}
