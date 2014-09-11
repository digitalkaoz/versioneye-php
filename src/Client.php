<?php

namespace Rs\VersionEye;

use Rs\VersionEye\Http\BuzzClient;
use Rs\VersionEye\Http\GuzzleClient;
use Rs\VersionEye\Http\HttpClient;

/**
 * Client for interacting with the API
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
     * @param HttpClient $client
     * @param string     $url
     */
    public function __construct(HttpClient $client = null, $url = 'https://www.versioneye.com/api/v2/')
    {
        $this->initializeClient($url, $client);
    }

    /**
     * returns an api
     *
     * @param  string $name
     * @return Api\Api
     * @throws \InvalidArgumentException
     */
    public function api($name)
    {
        $class = 'Rs\\VersionEye\\Api\\' . ucfirst($name);

        if (class_exists($class)) {
            return new $class($this->client, $this->token);
        } else {
            throw new \InvalidArgumentException('unknown api "' . $name . '" requested');
        }
    }

    /**
     * authorizes a api
     *
     * @param $token
     */
    public function authorize($token)
    {
        $this->token = $token;
    }

    /**
     * initializes the http client
     *
     * @param string     $url
     * @param HttpClient $client
     */
    private function initializeClient($url, HttpClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        } elseif (!$client && class_exists('\GuzzleHttp\Client')) {
            $this->client = new GuzzleClient($url);
        } elseif (!$client && class_exists('Buzz\Browser')) {
            $this->client = new BuzzClient($url);
        } else {
            throw new \RuntimeException('no suitable HTTP adapter found');
        }
    }
}
