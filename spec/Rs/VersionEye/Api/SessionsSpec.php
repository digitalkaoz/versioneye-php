<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SessionsSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Sessions');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_show(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'sessions')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->show()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_create(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'sessions')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->open()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_close(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('DELETE', 'sessions')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->close()->shouldBeArray();
    }

}
