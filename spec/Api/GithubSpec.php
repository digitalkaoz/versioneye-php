<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class GithubSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client, 4711);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Github');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    public function it_calls_the_correct_url_on_repos(Client $client)
    {
        $client->request('GET', 'github?lang=php&private=1&org_name=digitalkaoz&org_type=private&page=1&only_imported=1&api_key=4711', [])->willReturn([]);

        $this->repos('php', true, 'digitalkaoz', 'private', true)->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_sync(Client $client)
    {
        $client->request('GET', 'github/sync?api_key=4711', [])->willReturn([]);

        $this->sync()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'github/symfony:symfony?api_key=4711', [])->willReturn([]);

        $this->show('symfony/symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_import(Client $client)
    {
        $client->request('POST', 'github/symfony:symfony?branch=develop&file=composer.json&api_key=4711', [])->willReturn([]);

        $this->import('symfony/symfony', 'develop', 'composer.json')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_delete(Client $client)
    {
        $client->request('DELETE', 'github/symfony:symfony?branch=develop&file=composer.json&api_key=4711', [])->willReturn([]);

        $this->delete('symfony/symfony', 'develop', 'composer.json')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_hook(Client $client)
    {
        $client->request('POST', 'github/hook/project_id?api_key=4711', [])->willReturn([]);

        $this->hook('project_id')->shouldBeArray();
    }
}
