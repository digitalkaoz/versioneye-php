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
    private $token;

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
}
