<?php

namespace spec\Rs\VersionEye\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Post\PostBody;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectsSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Projects');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_all(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'projects')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->all()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_show(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'projects/symfony')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->show('symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_licenses(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('GET', 'projects/symfony/licenses')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->licenses('symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_delete(Client $client, Request $request, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('DELETE', 'projects/symfony')->willReturn($request);
        $client->send($request)->willReturn($response);

        $this->delete('symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_create(Client $client, Request $request, Response $response, PostBody $body)
    {
        $request->getBody()->shouldBeCalled()->willReturn($body);
        $body->addFile(Argument::type('GuzzleHttp\Post\PostFile'))->shouldBeCalled();

        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'projects')->willReturn($request);
        $client->send($request)->willReturn($response);

        $file = tempnam(sys_get_temp_dir(), 'versioneye');

        $this->create($file)->shouldBeArray();

        @unlink($file);
    }

    function it_calls_the_correct_url_on_update(Client $client, Request $request, Response $response, PostBody $body)
    {
        $request->getBody()->shouldBeCalled()->willReturn($body);
        $body->addFile(Argument::type('GuzzleHttp\Post\PostFile'))->shouldBeCalled();

        $response->json()->shouldBeCalled()->willReturn(array());

        $client->createRequest('POST', 'projects/symfony')->willReturn($request);
        $client->send($request)->willReturn($response);

        $file = tempnam(sys_get_temp_dir(), 'versioneye');

        $this->update('symfony', $file)->shouldBeArray();

        @unlink($file);
    }

}
