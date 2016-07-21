<?php

namespace spec\Rs\VersionEye\Api;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Api\BaseApi;
use Rs\VersionEye\Api\Projects;
use Rs\VersionEye\Http\HttpClient as Client;

class ProjectsSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Projects::class);
        $this->shouldHaveType(BaseApi::class);
        $this->shouldHaveType(Api::class);
    }

    public function it_calls_the_correct_url_on_all(Client $client)
    {
        $client->request('GET', 'projects', [])->willReturn([]);

        $this->all()->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_show_and_has_a_Pager_injected(Client $client)
    {
        $client->request('GET', 'projects/symfony', [])->willReturn(['repos' => [], 'paging' => ['current_page' => 1, 'total_entries' => 4]]);

        $this->show('symfony')->shouldBeArray();
        $this->show('symfony')['repos']->shouldHaveType('Rs\VersionEye\Http\Pager');
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

    public function it_calls_the_correct_url_on_merge(Client $client)
    {
        $client->request('GET', 'projects/1337/merge/42', [])->willReturn([]);

        $this->merge('1337', '42')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_mergeGa(Client $client)
    {
        $client->request('GET', 'projects/13/1337/merge_ga/42', [])->willReturn([]);

        $this->mergeGa('13', '1337', '42')->shouldBeArray();
    }

    public function it_calls_the_correct_url_on_unmerge(Client $client)
    {
        $client->request('GET', 'projects/1337/unmerge/42', [])->willReturn([]);

        $this->unmerge('1337', '42')->shouldBeArray();
    }
}
