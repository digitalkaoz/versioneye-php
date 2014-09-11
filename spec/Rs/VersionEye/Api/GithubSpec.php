<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rs\VersionEye\Http\HttpClient as Client;

class GithubSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client, 4711);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Github');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    function it_calls_the_correct_url_on_repos(Client $client)
    {
        $client->request('GET', 'github?lang=php&private=1&org_name=digitalkaoz&org_type=private&page=42&only_imported=1&api_key=4711', [])->willReturn([]);

        $this->repos('php', true, 'digitalkaoz', 'private', 42, true)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_sync(Client $client)
    {
        $client->request('GET', 'github/sync?force=1&api_key=4711', [])->willReturn([]);

        $this->sync(true)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_search(Client $client)
    {
        $client->request('GET', 'github/search?q=symfony&langs=php&users=fabpot&page=12&api_key=4711', [])->willReturn([]);

        $this->search('symfony', 'php', 'fabpot', 12)->shouldBeArray();
    }

    function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'github/symfony:symfony?api_key=4711', [])->willReturn([]);

        $this->show('symfony/symfony')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_import(Client $client)
    {
        $client->request('POST', 'github/symfony:symfony?branch=develop&api_key=4711', [])->willReturn([]);

        $this->import('symfony/symfony', 'develop')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_delete(Client $client)
    {
        $client->request('DELETE', 'github/symfony:symfony?branch=develop&api_key=4711', [])->willReturn([]);

        $this->delete('symfony/symfony', 'develop')->shouldBeArray();
    }

    function it_calls_the_correct_url_on_hook(Client $client)
    {
        $client->request('POST', 'github/hook/project_id?api_key=4711', [])->willReturn([]);

        $this->hook('project_id')->shouldBeArray();
    }

}
