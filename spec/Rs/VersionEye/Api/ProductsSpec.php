<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class ProductsSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Products');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    public function it_calls_the_correct_url_on_search(Client $client)
    {
        $client->request('GET', 'products/search/symfony?lang=php&g=foo&page=1', [])->willReturn([]);

        $this->search('symfony', 'php', 'foo')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'products/php/symfony', [])->willReturn([]);

        $this->show('php', 'symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_followStatus(Client $client)
    {
        $client->request('GET', 'products/php/symfony/follow', [])->willReturn([]);

        $this->followStatus('php', 'symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_follow(Client $client)
    {
        $client->request('POST', 'products/php/symfony/follow', [])->willReturn([]);

        $this->follow('php', 'symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_unfollow(Client $client)
    {
        $client->request('DELETE', 'products/php/symfony/follow', [])->willReturn([]);

        $this->unfollow('php', 'symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_references(Client $client)
    {
        $client->request('GET', 'products/php/symfony/references?page=1', [])->willReturn([]);

        $this->references('php', 'symfony')->shouldBeArray();
    }
}
