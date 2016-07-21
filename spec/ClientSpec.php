<?php

namespace spec\Rs\VersionEye;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Github;
use Rs\VersionEye\Api\Me;
use Rs\VersionEye\Api\Products;
use Rs\VersionEye\Api\Projects;
use Rs\VersionEye\Api\Services;
use Rs\VersionEye\Api\Users;
use Rs\VersionEye\Client;
use Rs\VersionEye\Http\HttpClient;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_exposes_the_github_api()
    {
        $this->api('github')->shouldHaveType(Github::class);
    }

    public function it_exposes_the_me_api()
    {
        $this->api('me')->shouldHaveType(Me::class);
    }

    public function it_exposes_the_products_api()
    {
        $this->api('products')->shouldHaveType(Products::class);
    }

    public function it_exposes_the_projects_api()
    {
        $this->api('projects')->shouldHaveType(Projects::class);
    }

    public function it_exposes_the_services_api()
    {
        $this->api('services')->shouldHaveType(Services::class);
    }

    public function it_exposes_the_users_api()
    {
        $this->api('users')->shouldHaveType(Users::class);
    }

    public function it_creates_authorized_apis_if_client_is_authorized(HttpClient $client)
    {
        $this->beConstructedWith($client);
        $this->authorize('lolcat'); //mh cant test it as the auth will be injected by http-plug/plugins auth

        $client->request('GET', 'me', [])->shouldBeCalled();

        $this->api('me')->profile();
    }

    public function it_doesnt_exposes_a_unknown_api()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('api', ['foo']);
    }
}
