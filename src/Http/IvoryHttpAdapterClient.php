<?php

namespace Rs\VersionEye\Http;

use Ivory\HttpAdapter\HttpAdapterException;
use Ivory\HttpAdapter\HttpAdapterInterface;

/**
 * IvoryHttpAdapterClient.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class IvoryHttpAdapterClient implements HttpClient
{
    /**
     * @var HttpAdapterInterface
     */
    private $adapter;

    private $url;

    /**
     * @param HttpAdapterInterface $adapter
     * @param string               $url
     */
    public function __construct(HttpAdapterInterface $adapter, $url)
    {
        $this->adapter = $adapter;
        $this->url     = $url;
    }

    /**
     * @inheritdoc
     */
    public function request($method, $url, array $params = [])
    {
        list($params, $files) = $this->fixParams($params);

        try {
            $response = $this->adapter->send($this->url . $url, $method, [], $params, $files);

            return json_decode($response->getBody(), true);
        } catch (HttpAdapterException $e) {
            $data    = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : ['error' => $e->getMessage()];
            $message = is_array($data) && isset($data['error']) ? $data['error'] : 'Server Error';

            $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;

            throw new CommunicationException(sprintf('%s : %s', $status, $message));
        }
    }

    /**
     * splits arguments into parameters and files (if any).
     *
     * @param array $params
     *
     * @return array
     */
    private function fixParams(array $params)
    {
        $parameters = [];
        $files      = [];

        foreach ($params as $name => $value) {
            if (is_readable($value)) { //file
                $files[$name] = $value;
            } else {
                $parameters[$name] = $value;
            }
        }

        return [$parameters, $files];
    }
}
