<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class MeSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Me');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    public function it_calls_the_correct_url_on_profile(Client $client)
    {
        $client->request('GET', 'me', [])->willReturn([]);

        $this->profile()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_profile_with_auth(Client $client)
    {
        $this->beConstructedWith($client, 'lolcat');

        $client->request('GET', 'me?api_key=lolcat', [])->willReturn([]);

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
