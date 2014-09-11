<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rs\VersionEye\Http\HttpClient as Client;

class UsersSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Users');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz', [])->willReturn([]);

        $this->show('digitalkaoz')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_favorites(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz/favorites?page=2', [])->willReturn([]);

        $this->favorites('digitalkaoz', 2)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_comments(Client $client)
    {
        $client->request('GET', 'users/digitalkaoz/comments?page=2', [])->willReturn([]);

        $this->comments('digitalkaoz', 2)->shouldBeArray();
    }

}
