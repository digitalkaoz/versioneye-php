<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServicesSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Services');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_ping(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'services/ping')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->ping()->shouldBeArray();
    }
}
