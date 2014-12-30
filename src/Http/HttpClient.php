<?php


namespace Rs\VersionEye\Http;

use Ivory\HttpAdapter\HttpAdapterException;
use Ivory\HttpAdapter\HttpAdapterInterface;

/**
 * HttpClient
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class HttpClient
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
        $this->url = $url;
    }

    /**
     * performs a HTTP Request to the API Endpoint
     *
     * @param  string                 $method
     * @param  string                 $url
     * @param  array                  $params
     * @return array
     * @throws CommunicationException
     */
    public function request($method, $url, array $params = [])
    {
        list($params, $files) = $this->fixParams($params);

        try {
            $response = $this->adapter->send($this->url.$url, $method, [], $params, $files);

            return json_decode($response->getBody(), true);
        } catch (HttpAdapterException $e) {
            $data = json_decode($e->getResponse()->getBody(), true);
            $message = is_array($data) && isset($data['error']) ? $data['error'] : 'Server Error';

            throw new CommunicationException($e->getResponse()->getStatusCode().' : '.$message);
        }
    }

    /**
     * splits arguments into parameters and files (if any)
     *
     * @param  array $params
     * @return array
     */
    private function fixParams(array $params)
    {
        $parameters = [];
        $files = [];

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
