<?php

namespace Rs\VersionEye\Http;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Exception\HttpException as PlugException;
use Http\Client\HttpClient as PlugClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\RequestInterface;

/**
 * HttpPlugHttpAdapterClient.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class HttpPlugHttpAdapterClient implements HttpClient
{
    /**
     * @var HttpMethodsClient
     */
    private $adapter;

    private $url;

    /**
     * @param PlugClient $adapter
     * @param string     $url
     */
    public function __construct(PlugClient $adapter, $url)
    {
        $this->adapter = $adapter;
        $this->url     = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, array $params = [])
    {
        list($params, $files) = $this->splitParams($params);
        $url                  = $this->url . $path;

        try {
            $request  = $this->createBody($params, $files, $method, $url);
            $response = $request ? $this->adapter->sendRequest($request) : $this->adapter->send($method, $url);

            return json_decode($response->getBody(), true);
        } catch (PlugException $e) {
            throw $this->buildRequestError($e);
        }
    }

    /**
     * splits arguments into parameters and files (if any).
     *
     * @param array $params
     *
     * @return array
     */
    private function splitParams(array $params)
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

    /**
     * builds the error exception.
     *
     * @param PlugException $e
     *
     * @return CommunicationException
     */
    private function buildRequestError(PlugException $e)
    {
        $data    = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : ['error' => $e->getMessage()];
        $message = isset($data['error']) ? $data['error'] : 'Server Error';
        $status  = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;

        return new CommunicationException(sprintf('%s : %s', $status, $message));
    }

    /**
     * @param array  $params
     * @param array  $files
     * @param string $method
     * @param string $url
     *
     * @return null|RequestInterface
     */
    private function createBody(array $params, array $files, $method, $url)
    {
        if (!count($params) && !count($files)) {
            return;
        }

        $streamFactory = StreamFactoryDiscovery::find();
        $builder       = new MultipartStreamBuilder($streamFactory);

        foreach ($params as $k => $v) {
            $builder->addResource($k, $v);
        }

        foreach ($files as $k => $file) {
            $builder->addResource($k, fopen($file, 'r'), ['filename' => $file]);
        }

        return MessageFactoryDiscovery::find()->createRequest(
            $method,
            $url,
            ['Content-Type' => 'multipart/form-data; boundary=' . $builder->getBoundary()],
            $builder->build()
        );
    }
}
