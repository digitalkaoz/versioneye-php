<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Sessions;
use Rs\VersionEye\Http\HttpClient as Client;

class SessionsSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Sessions::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'sessions', [])->willReturn([]);

        $this->show()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_create(Client $client)
    {
        $client->request('POST', 'sessions', [])->willReturn([]);

        $this->open()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_close(Client $client)
    {
        $client->request('DELETE', 'sessions', [])->willReturn([]);

        $this->close()->shouldBeArray();
    }
}
