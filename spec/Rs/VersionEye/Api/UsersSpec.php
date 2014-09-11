<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UsersSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Users');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_show(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'users/digitalkaoz')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->show('digitalkaoz')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_favorites(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'users/digitalkaoz/favorites?page=2')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->favorites('digitalkaoz', 2)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_comments(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'users/digitalkaoz/comments?page=2')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->comments('digitalkaoz', 2)->shouldBeArray();
    }

}
