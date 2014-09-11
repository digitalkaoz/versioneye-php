<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GithubSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client, 4711);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Github');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_repos(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'github?lang=php&private=1&org_name=digitalkaoz&org_type=private&page=42&only_imported=1&api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->repos('php', true, 'digitalkaoz', 'private', 42, true)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_sync(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'github/sync?force=1&api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->sync(true)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_search(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'github/search?q=symfony&langs=php&users=fabpot&page=12&api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->search('symfony', 'php', 'fabpot', 12)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_show(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'github/symfony:symfony?api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->show('symfony/symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_import(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'github/symfony:symfony?branch=develop&api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->import('symfony/symfony', 'develop')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_delete(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('DELETE', 'github/symfony:symfony?branch=develop&api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->delete('symfony/symfony', 'develop')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_hook(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'github/hook/project_id?api_key=4711')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->hook('project_id')->shouldBeArray();
    }

}
