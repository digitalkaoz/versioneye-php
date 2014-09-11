<?php
/**
 * versioneye-php
 */

namespace Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Namshi\Cuzzle\Formatter\CurlFormatter;


/**
 * BaseApi
 * @author Robert Schönthal <robert.schoenthal@sinnerschrader.com>
 */
class BaseApi
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
    public function __construct(Client $client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * performs the request
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @return array
     */
    protected function request($url, $method = 'GET', array $params = array())
    {
        if ($this->token) {
            if(strstr($url, '?')) {
                $url .= '&api_key='.$this->token;
            } else {
                $url .= '?api_key='.$this->token;
            }
        }

        $request = $this->client->createRequest($method, $url);
        $response = $this->client->send($request);

        /** @var Response $response */
        //echo (new CurlFormatter())->format($request);

        return $response->json();
    }

    /**
     * converts names to the needed url path format
     *
     * @param string $name
     * @return string
     */
    protected function transform($name)
    {
        return str_replace(array('/', '.'), array(':', '~'), $name);
    }
}