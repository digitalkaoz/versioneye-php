<?php


namespace Rs\VersionEye\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostFile;

/**
 * GuzzleClient
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GuzzleClient implements HttpClient
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param                 $url    ;
     * @param ClientInterface $client
     */
    public function __construct($url, ClientInterface $client = null)
    {
        $this->client = $client ?: new \GuzzleHttp\Client(['base_url' => $url]);
    }

    /**
     * @inheritdoc
     */
    public function request($method, $url, array $params = [])
    {
        $request = $this->client->createRequest($method, $url);

        if ($params) {
            $this->addParameters($params, $request);
        }

        return $this->client->send($request)->json();
    }

    /**
     * add parameters to request if present (e.g. for file uploads)
     *
     * @param array            $params
     * @param RequestInterface $request
     */
    private function addParameters(array $params, RequestInterface $request)
    {
        foreach ($params as $name => $value) {
            if (is_readable($value)) { //upload
                $request->getBody()->addFile(new PostFile($name, fopen($value, 'r')));
            }
        }
    }
}
