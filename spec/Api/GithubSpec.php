<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Github;
use Rs\VersionEye\Http\HttpClient as Client;

class GithubSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client, 4711);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Github::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_repos(Client $client)
    {
        $client->request('GET', 'github?lang=php&private=1&org_name=digitalkaoz&org_type=private&page=1&only_imported=1', [])->willReturn([]);

        $this->repos('php', true, 'digitalkaoz', 'private', true)->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_sync(Client $client)
    {
        $client->request('GET', 'github/sync', [])->willReturn([]);

        $this->sync()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'github/symfony:symfony', [])->willReturn([]);

        $this->show('symfony/symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_import(Client $client)
    {
        $client->request('POST', 'github/symfony:symfony?branch=develop&file=composer.json', [])->willReturn([]);

        $this->import('symfony/symfony', 'develop', 'composer.json')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_delete(Client $client)
    {
        $client->request('DELETE', 'github/symfony:symfony?branch=develop&file=composer.json', [])->willReturn([]);

        $this->delete('symfony/symfony', 'develop', 'composer.json')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_hook(Client $client)
    {
        $client->request('POST', 'github/hook/project_id', [])->willReturn([]);

        $this->hook('project_id')->shouldBeArray();
    }
}
