<?php

namespace spec\Rs\VersionEye\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Post\PostBodyInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuzzleClientSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith('http://lolcathost/', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Http\GuzzleClient');
        $this->shouldHaveType('Rs\VersionEye\Http\HttpClient');
    }

    public function it_performs_a_GET_request_to_given_url(ClientInterface $client, RequestInterface $request, ResponseInterface $response)
    {
        $client->createRequest('GET', 'bar')->shouldBeCalled()->willReturn($request);
        $client->send($request)->shouldBeCalled()->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn([]);

        $this->request('GET', 'bar')->shouldBeArray();
    }

    public function it_performs_a_DELETE_request_to_given_url(ClientInterface $client, RequestInterface $request, ResponseInterface $response)
    {
        $client->createRequest('DELETE', 'bar')->shouldBeCalled()->willReturn($request);
        $client->send($request)->shouldBeCalled()->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn([]);

        $this->request('DELETE', 'bar')->shouldBeArray();
    }

    public function it_performs_a_PUT_request_to_given_url(ClientInterface $client, RequestInterface $request, ResponseInterface $response)
    {
        $client->createRequest('PUT', 'bar')->shouldBeCalled()->willReturn($request);
        $client->send($request)->shouldBeCalled()->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn([]);

        $this->request('PUT', 'bar')->shouldBeArray();
    }

    public function it_performs_a_POST_request_to_given_url(ClientInterface $client, RequestInterface $request, ResponseInterface $response, PostBodyInterface $body)
    {
        $client->createRequest('POST', 'bar')->shouldBeCalled()->willReturn($request);
        $client->send($request)->shouldBeCalled()->willReturn($response);

        $request->getBody()->shouldBeCalled()->willReturn($body);
        $body->addFile(Argument::type('GuzzleHttp\Post\PostFile'))->shouldBeCalled();

        $response->json()->shouldBeCalled()->willReturn([]);

        $file = tempnam(sys_get_temp_dir(), 'veye');

        $this->request('POST', 'bar', ['foo' => $file])->shouldBeArray();

        @unlink($file);
    }
}
