<?php

namespace Rs\VersionEye\Api;

use GuzzleHttp\Client;

/**
 * ServicesApi
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Services implements Api
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Answers to request with basic pong.
     *
     * @return array
     */
    public function ping()
    {
        return $this->client->get('services/ping')->json();
    }
}
