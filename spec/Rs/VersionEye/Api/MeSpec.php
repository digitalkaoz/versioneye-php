<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MeSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Me');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_profile(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'me')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->profile()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_favorites(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'me/favorites')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->favorites()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_comments(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'me/comments')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->comments()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_notifications(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'me/notifications')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->notifications()->shouldBeArray();
    }

}
