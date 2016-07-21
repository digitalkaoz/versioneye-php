<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Me;
use Rs\VersionEye\Http\HttpClient as Client;

class MeSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Me::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_profile(Client $client)
    {
        $client->request('GET', 'me', [])->willReturn([]);

        $this->profile()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_profile_with_auth(Client $client)
    {
        $client->request('GET', 'me', [])->willReturn([]);

        $this->profile()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_favorites(Client $client)
    {
        $client->request('GET', 'me/favorites', [])->willReturn([]);

        $this->favorites()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_comments(Client $client)
    {
        $client->request('GET', 'me/comments', [])->willReturn([]);

        $this->comments()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_notifications(Client $client)
    {
        $client->request('GET', 'me/notifications', [])->willReturn([]);

        $this->notifications()->shouldBeArray();
    }
}
