<?php

namespace Rs\VersionEye\Http;

use Http\Client\Exception\HttpException as PlugException;
use Http\Client\HttpClient as PlugClient;
use Http\Message\MessageFactory;
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
     * @var PlugClient
     */
    private $adapter;

    private $url;
    /**
     * @var MessageFactory
     */
    private $factory;
    /**
     * @var MultipartStreamBuilder
     */
    private $multipartStreamBuilder;

    /**
     * @param PlugClient             $adapter
     * @param string                 $url
     * @param MessageFactory         $factory
     * @param MultipartStreamBuilder $multipartStreamBuilder
     */
    public function __construct(PlugClient $adapter, $url, MessageFactory $factory, MultipartStreamBuilder $multipartStreamBuilder)
    {
        $this->adapter                = $adapter;
        $this->url                    = $url;
        $this->factory                = $factory;
        $this->multipartStreamBuilder = $multipartStreamBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, array $params = [])
    {
        list($params, $files) = $this->splitParams($params);
        $url                  = $this->url . $path;

        try {
            $request  = $this->createRequest($params, $files, $method, $url);
            $response = $this->adapter->sendRequest($request);

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
     * @return RequestInterface
     */
    private function createRequest(array $params, array $files, $method, $url)
    {
        if (!count($params) && !count($files)) {
            return $this->factory->createRequest($method, $url);
        }

        $builder = clone $this->multipartStreamBuilder;

        foreach ($params as $k => $v) {
            $builder->addResource($k, $v);
        }

        foreach ($files as $k => $file) {
            $builder->addResource($k, fopen($file, 'r'), ['filename' => $file]);
        }

        return $this->factory->createRequest(
            $method,
            $url,
            ['Content-Type' => 'multipart/form-data; boundary=' . $builder->getBoundary()],
            $builder->build()
        );
    }
}
