<?php

namespace spec\Rs\VersionEye\Http;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Exception\HttpException;
use Http\Message\MessageFactory;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rs\VersionEye\Http\CommunicationException;
use Rs\VersionEye\Http\HttpClient;
use Rs\VersionEye\Http\HttpPlugHttpAdapterClient;

class HttpPlugHttpAdapterClientSpec extends ObjectBehavior
{
    public function let(HttpMethodsClient $client, MessageFactory $factory, MultipartStreamBuilder $builder, RequestInterface $request)
    {
        $this->beConstructedWith($client, 'http://lolcathost/', $factory, $builder);

        $factory->createRequest(Argument::type('string'), Argument::type('string'), Argument::any(), Argument::any())->willReturn($request);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(HttpPlugHttpAdapterClient::class);
        $this->shouldHaveType(HttpClient::class);
    }

    public function it_performs_a_HTTP_request_to_the_given_url(HttpMethodsClient $client, ResponseInterface $response)
    {
        $client->sendRequest(Argument::type(RequestInterface::class))->willReturn($response);

        $response->getBody()->willReturn('[]');

        $this->request('GET', 'bar')->shouldBeArray();
    }

    public function it_performs_a_HTTP_request_to_the_given_url_and_raises_an_error_if_failed(HttpMethodsClient $client, ResponseInterface $response, HttpException $e)
    {
        $client->sendRequest(Argument::type(RequestInterface::class))->willThrow($e->getWrappedObject());
        $e->getResponse()->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn('{"error":"foo"}');

        $this->shouldThrow(new CommunicationException('500 : foo'))->during('request', ['GET', 'bar']);
    }

    public function it_performs_a_HTTP_request_to_the_given_url_with_params(HttpMethodsClient $client, ResponseInterface $response)
    {
        $client->sendRequest(Argument::type(RequestInterface::class))->willReturn($response);

        $response->getBody()->willReturn('[]');

        $this->request('DELETE', 'bar', ['foo' => 'bar'])->shouldBeArray();
    }

    public function it_performs_a_HTTP_request_to_the_given_url_with_params_and_files(HttpMethodsClient $client, ResponseInterface $response)
    {
        $file = tempnam(sys_get_temp_dir(), 'veye');

        $client->sendRequest(Argument::type(RequestInterface::class))->willReturn($response);

        $response->getBody()->willReturn('[]');

        $this->request('DELETE', 'bar', ['foo' => 'bar', 'bazz' => $file])->shouldBeArray();

        @unlink($file);
    }
}
