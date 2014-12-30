<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class ProjectsSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Api\Projects');
        $this->shouldHaveType('Rs\VersionEye\Api\BaseApi');
        $this->shouldHaveType('Rs\VersionEye\Api\Api');
    }

    public function it_calls_the_correct_url_on_all(Client $client)
    {
        $client->request('GET', 'projects', [])->willReturn([]);

        $this->all()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_show(Client $client)
    {
        $client->request('GET', 'projects/symfony', [])->willReturn([]);

        $this->show('symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_licenses(Client $client)
    {
        $client->request('GET', 'projects/symfony/licenses', [])->willReturn([]);

        $this->licenses('symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_delete(Client $client)
    {
        $client->request('DELETE', 'projects/symfony', [])->willReturn([]);

        $this->delete('symfony')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_create(Client $client)
    {
        $client->request('POST', 'projects', ['upload' => 'path/to/file'])->willReturn([]);

        $this->create('path/to/file')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_update(Client $client)
    {
        $client->request('POST', 'projects/symfony', ['project_file' => 'path/to/file'])->willReturn([]);

        $this->update('symfony', 'path/to/file')->shouldBeArray();
    }
}
