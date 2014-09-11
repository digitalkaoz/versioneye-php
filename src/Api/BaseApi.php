<?php

namespace Rs\VersionEye\Api;

use Rs\VersionEye\Http\HttpClient;

/**
 * BaseApi
 *
 * @author Robert Schönthal <robert.schoenthal@sinnerschrader.com>
 */
abstract class BaseApi
{
    /**
     * @var HttpClient
     */
    protected $client;
    private $token;

    /**
     * @param HttpClient $client
     * @param string     $token
     */
    public function __construct(HttpClient $client, $token = null)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * performs the request
     *
     * @param  string $url
     * @param  string $method
     * @param  array  $params
     * @return array
     */
    protected function request($url, $method = 'GET', array $params = [])
    {
        if ($this->token) {
            $delimiter = strstr($url, '?') ? '&' : '?';

            $url = sprintf('%s%sapi_key=%s', $url, $delimiter, $this->token);
        }

        return $this->client->request($method, $url, $params);
    }

    /**
     * converts names to the needed url path format
     *
     * @param  string $name
     * @return string
     */
    protected function transform($name)
    {
        return str_replace(['/', '.'], [':', '~'], $name);
    }
}
