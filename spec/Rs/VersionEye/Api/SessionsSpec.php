<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rs\VersionEye\Http\HttpClient as Client;

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

    function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'sessions', [])->willReturn([]);
        
        $this->show()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_create(Client $client)
    {
        $client->request('POST', 'sessions', [])->willReturn([]);

        $this->open()->shouldBeArray();
    }

    function it_calls_the_correct_url_on_close(Client $client)
    {
        $client->request('DELETE', 'sessions', [])->willReturn([]);

        $this->close()->shouldBeArray();
    }

}
