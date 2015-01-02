<?php

namespace Rs\VersionEye;

use Ivory\HttpAdapter\BuzzHttpAdapter;
use Ivory\HttpAdapter\Configuration;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Ivory\HttpAdapter\FopenHttpAdapter;
use Ivory\HttpAdapter\GuzzleHttpAdapter;
use Ivory\HttpAdapter\GuzzleHttpHttpAdapter;
use Ivory\HttpAdapter\HttpAdapterFactory;
use Ivory\HttpAdapter\Zend1HttpAdapter;
use Ivory\HttpAdapter\Zend2HttpAdapter;
use Rs\VersionEye\Http\HttpClient;
use Rs\VersionEye\Api\Api;

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
     * @param  string                    $name
     * @return Api
     * @throws \InvalidArgumentException
     */
    public function api($name)
    {
        $class = 'Rs\\VersionEye\\Api\\'.ucfirst($name);

        if (class_exists($class)) {
            return new $class($this->client, $this->token);
        } else {
            throw new \InvalidArgumentException('unknown api "'.$name.'" requested');
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
     * @param  string     $url
     * @param  HttpClient $client
     * @return HttpClient
     */
    private function initializeClient($url, HttpClient $client = null)
    {
        if ($client) {
            return $this->client = $client;
        }

        return $this->client = new HttpClient(HttpAdapterFactory::guess(), $url);
    }
}
