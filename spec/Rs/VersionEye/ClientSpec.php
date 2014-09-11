<?php

namespace spec\Rs\VersionEye;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient;

class ClientSpec extends ObjectBehavior
{
    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client, 'http://lolcathost');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Client');
    }

    public function it_exposes_the_github_api()
    {
        $this->api('github')->shouldHaveType('Rs\VersionEye\Api\Github');
    }

    public function it_exposes_the_me_api()
    {
        $this->api('me')->shouldHaveType('Rs\VersionEye\Api\Me');
    }

    public function it_exposes_the_products_api()
    {
        $this->api('products')->shouldHaveType('Rs\VersionEye\Api\Products');
    }

    public function it_exposes_the_projects_api()
    {
        $this->api('projects')->shouldHaveType('Rs\VersionEye\Api\Projects');
    }

    public function it_exposes_the_services_api()
    {
        $this->api('services')->shouldHaveType('Rs\VersionEye\Api\Services');
    }

    public function it_exposes_the_users_api()
    {
        $this->api('users')->shouldHaveType('Rs\VersionEye\Api\Users');
    }

    public function it_doesnt_exposes_a_unknown_api()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('api', ['foo']);
    }
}
