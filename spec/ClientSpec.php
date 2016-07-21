<?php

namespace spec\Rs\VersionEye;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient as Client;

class ClientSpec extends ObjectBehavior
{
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

    public function it_creates_authorized_apis_if_client_is_authorized(Client $client)
    {
        $this->beConstructedWith($client);
        $this->authorize('lolcat'); //mh cant test it as the auth will be injected by http-plug/plugins auth

        $client->request('GET', 'me', [])->shouldBeCalled();

        $this->api('me')->profile();
    }

    public function it_doesnt_exposes_a_unknown_api()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('api', ['foo']);
    }
}
