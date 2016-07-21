<?php

namespace Rs\VersionEye;

use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Http\ClientFactory;
use Rs\VersionEye\Http\HttpClient;

/**
 * Client for interacting with the API.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Client
{
    /**
     * @var HttpClient
     */
    private $client;

    private $token;
    /**
     * @var string
     */
    private $url;

    /**
     * @param HttpClient $client
     * @param string     $url
     */
    public function __construct(HttpClient $client = null, $url = 'https://www.versioneye.com/api/v2/')
    {
        $this->client = $client;
        $this->url    = $url;
    }

    /**
     * returns an api.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return Api
     */
    public function api($name)
    {
        $this->initializeClient($this->url, $this->client);

        $class = 'Rs\\VersionEye\\Api\\' . ucfirst($name);

        if (class_exists($class)) {
            return new $class($this->client);
        } else {
            throw new \InvalidArgumentException('unknown api "' . $name . '" requested');
        }
    }

    /**
     * authorizes a api.
     *
     * @param $token
     */
    public function authorize($token)
    {
        $this->token = $token;
    }

    /**
     * initializes the http client.
     *
     * @param string     $url
     * @param HttpClient $client
     *
     * @return HttpClient
     */
    private function initializeClient($url, HttpClient $client = null)
    {
        if ($client) {
            return $this->client = $client;
        }

        return $this->client = $this->createDefaultHttpClient($url);
    }

    /**
     * @param string $url
     *
     * @return HttpClient
     */
    private function createDefaultHttpClient($url)
    {
        return ClientFactory::create($url, $this->token);
    }
}
