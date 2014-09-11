<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductsSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Products');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_search(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'products/search/symfony?lang=php&g=foo&page=12')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->search('symfony','php','foo',12)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_show(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'products/php/symfony')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->show('php', 'symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_followStatus(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'products/php/symfony/follow')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->followStatus('php', 'symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_follow(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'products/php/symfony/follow')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->follow('php', 'symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_unfollow(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('DELETE', 'products/php/symfony/follow')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->unfollow('php', 'symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_references(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'products/php/symfony/references?page=0')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->references('php', 'symfony')->shouldBeArray();
    }

}
