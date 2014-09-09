<?php

namespace Rs\VersionEye;

/**
 * Client
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(\GuzzleHttp\Client $client = null)
    {
        $this->client = $client ?: new \GuzzleHttp\Client(array('base_url' => 'https://www.versioneye.com/api/v2/'));
    }

    /**
     * returns an api
     *
     * @param  string                    $name
     * @return Api\Api
     * @throws \InvalidArgumentException
     */
    public function api($name)
    {
        switch ($name) {
            case 'services' : return new Api\Services($this->client); break;
            case 'products' : return new Api\Products($this->client); break;
            default : throw new \InvalidArgumentException('unknown api "'.$name.'" requested');
        }
    }
}
