<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Users;
use Rs\VersionEye\Http\HttpClient as Client;

class UsersSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Users::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz', [])->willReturn([]);

        $this->show('digitalkaoz')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_favorites(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz/favorites?page=1', [])->willReturn([]);

        $this->favorites('digitalkaoz')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_comments(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz/comments?page=1', [])->willReturn([]);

        $this->comments('digitalkaoz')->shouldBeArray();
    }
}
