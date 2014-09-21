<?php

namespace Rs\VersionEye\Http;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;

/**
 * ZendClient
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ZendClient implements HttpClient
{
    /**
     * @var Client
     */
    private $client;
    private $url;

    /**
     * @param string $url
     * @param Client $client
     */
    public function __construct($url, Client $client = null)
    {
        $this->client = $client ?: new Client();
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function request($method, $url, array $params = [])
    {
        $request = new Request();

        $request->setMethod($method);

        if ($params) {
            $this->modifyParameters($params, $request);
        }

        $request->setUri($this->url.$url);
        $response = $this->client->send($request);

        return $this->processResponse($response);
    }

    /**
     * injects file uploads if needed
     *
     * @param array   $params
     * @param Request $request
     */
    private function modifyParameters(array $params, Request $request)
    {
        foreach ($params as $name => $value) {
            if (is_readable($value)) { //upload
                $request->getFiles()->set(basename($value), array(
                    'formname' => $name,
                    'filename' => basename($value),
                    'ctype'    => mime_content_type($value),
                    'data'     => file_get_contents($value)
                ));
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

        if ($response->isSuccess()) {
            return $data;
        }

        $message = is_array($data) && isset($data['error']) ? $data['error'] : 'Server Error';

        throw new CommunicationException($response->getStatusCode() . ' : ' . $message);
    }

}
