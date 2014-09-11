<?php

namespace Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Post\PostFile;

/**
 * BaseApi
 *
 * @author Robert Schönthal <robert.schoenthal@sinnerschrader.com>
 */
abstract class BaseApi
{
    /**
     * @var Client
     */
    protected $client;
    private $token;

    /**
     * @param Client $client
     * @param string $token
     */
    public function __construct(Client $client, $token = null)
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
    protected function request($url, $method = 'GET', array $params = array())
    {
        if ($this->token) {
            $url = $this->addAuthentication($url);
        }

        $request = $this->client->createRequest($method, $url);

        if ($params) {
            $this->addParameters($params, $request);
        }

        return $this->client->send($request)->json();
    }

    /**
     * converts names to the needed url path format
     *
     * @param  string $name
     * @return string
     */
    protected function transform($name)
    {
        return str_replace(array('/', '.'), array(':', '~'), $name);
    }

    /**
     * add parameters to request if present (e.g. for file uploads)
     *
     * @param array   $params
     * @param Request $request
     */
    private function addParameters(array $params, Request $request)
    {
        foreach ($params as $name => $value) {
            if (is_readable($value)) { //upload
                $request->getBody()->addFile(new PostFile($name, fopen($value, 'r')));
            }
        }
    }

    /**
     * add authentication to url if present
     *
     * @param  string $url
     * @return string
     */
    private function addAuthentication($url)
    {
        $delimiter = strstr($url, '?') ? '&' : '?';

        return sprintf('%s%sapi_key=%s', $url, $delimiter, $this->token);
    }
}
