<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class ServicesSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Services');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    public function it_calls_the_correct_url_on_ping(Client $client)
    {
        $client->request('GET', 'services/ping', [])->willReturn([]);

        $this->ping()->shouldBeArray();
    }
}
