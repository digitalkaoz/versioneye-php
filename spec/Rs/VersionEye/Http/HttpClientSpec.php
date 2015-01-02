<?php

namespace spec\Rs\VersionEye\Http;

use Ivory\HttpAdapter\ConfigurationInterface;
use Ivory\HttpAdapter\HttpAdapterException;
use Ivory\HttpAdapter\Message\ResponseInterface;
use PhpSpec\ObjectBehavior;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Rs\VersionEye\Http\CommunicationException;

class HttpClientSpec extends ObjectBehavior
{
    public function let(HttpAdapterInterface $client, ConfigurationInterface $config)
    {
        $this->beConstructedWith($client, 'http://lolcathost/');
        $client->getConfiguration()->willReturn($config);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Http\HttpClient');
    }

    public function it_performs_a_HTTP_request_to_the_given_url(HttpAdapterInterface $client, ResponseInterface $response)
    {
        $client->send('http://lolcathost/bar', 'GET', [], [], [])->shouldBeCalled()->willReturn($response);

        $response->getBody()->shouldBeCalled()->willReturn('[]');

        $this->request('GET', 'bar')->shouldBeArray();
    }

    public function it_performs_a_HTTP_request_to_the_given_url_and_raises_an_error_if_failed(HttpAdapterInterface $client, ResponseInterface $response, HttpAdapterException $e)
    {
        $client->send('http://lolcathost/bar', 'GET', [], [], [])->willThrow($e->getWrappedObject());
        $e->getResponse()->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->shouldBeCalled()->willReturn('{"error":"foo"}');

        $this->shouldThrow(new CommunicationException('500 : foo'))->during('request', ['GET', 'bar']);
    }

    public function it_performs_a_HTTP_request_to_the_given_url_with_params(HttpAdapterInterface $client, ResponseInterface $response)
    {
        $client->send('http://lolcathost/bar', 'DELETE', [], ['foo' => 'bar'], [])->shouldBeCalled()->willReturn($response);

        $response->getBody()->shouldBeCalled()->willReturn('[]');

        $this->request('DELETE', 'bar', ['foo' => 'bar'])->shouldBeArray();
    }

    public function it_performs_a_HTTP_request_to_the_given_url_with_params_and_files(HttpAdapterInterface $client, ResponseInterface $response)
    {
        $file = tempnam(sys_get_temp_dir(), 'veye');

        $client->send('http://lolcathost/bar', 'DELETE', [], ['foo' => 'bar'], ['bazz' => $file])->shouldBeCalled()->willReturn($response);

        $response->getBody()->shouldBeCalled()->willReturn('[]');

        $this->request('DELETE', 'bar', ['foo' => 'bar', 'bazz' => $file])->shouldBeArray();

        @unlink($file);
    }
}
