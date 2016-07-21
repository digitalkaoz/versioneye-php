<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Services;
use Rs\VersionEye\Http\HttpClient as Client;

class ServicesSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Services::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_ping(Client $client)
    {
        $client->request('GET', 'services/ping', [])->willReturn([]);

        $this->ping()->shouldBeArray();
    }
}
