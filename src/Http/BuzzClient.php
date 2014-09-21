<?php


namespace Rs\VersionEye\Http;

use Buzz\Browser;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response;

/**
 * BuzzClient
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BuzzClient implements HttpClient
{
    private $url;

    /**
     * @var Browser
     */
    private $client;

    /**
     * @param string  $url
     * @param Browser $client
     */
    public function __construct($url, Browser $client = null)
    {
        $this->url = $url;
        $this->client = $client ?: new Browser();
    }

    /**
     * performs a HTTP Request to the API Endpoint
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $params
     * @return array
     */
    public function request($method, $url, array $params = [])
    {
        $url = $this->url.$url;

        if ($params) {
            $this->modifyParameters($params);
        }
        $response = $this->client->submit($url, $params, $method);
        /** @var Response $response */

        return $this->processResponse($response);
    }

    /**
     * modify parameters (e.g. for file uploads)
     *
     * @param array $params
     */
    private function modifyParameters(array &$params)
    {
        foreach ($params as $name => $value) {
            if (is_readable($value)) { //upload
                $params[$name] = new FormUpload($value);
            }
        }
    }

    /**
     * processes the response
     *
     * @param  Response               $response
     * @return mixed
     * @throws CommunicationException
     */
    private function processResponse(Response $response)
    {
        $data = json_decode($response->getContent(), true);

        if ($response->isSuccessful()) {
            return $data;
        }

        $message = is_array($data) && isset($data['error']) ? $data['error'] : 'Server Error';

        throw new CommunicationException($response->getStatusCode() . ' : ' . $message);
    }

}
