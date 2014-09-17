<?php

namespace spec\Rs\VersionEye\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Stdlib\ParametersInterface;

class ZendClientSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith('http://lolcathost/', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Http\ZendClient');
        $this->shouldHaveType('Rs\VersionEye\Http\HttpClient');
    }

    public function it_performs_a_GET_request_to_given_url(Client $client, Response $response)
    {
        $client->send(Argument::that(function($arg){
            return $arg instanceof Request && $arg->getMethod() == 'GET' && $arg->getUri() == "http://lolcathost/bar";
        }))->shouldBeCalled()->willReturn($response);

        $response->isSuccess()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('GET', 'bar')->shouldBeArray();
    }

    public function it_performs_a_PUT_request_to_given_url(Client $client, Response $response)
    {
        $client->send(Argument::that(function($arg){
            return $arg instanceof Request && $arg->getMethod() == 'PUT' && $arg->getUri() == "http://lolcathost/bar";
        }))->shouldBeCalled()->willReturn($response);

        $response->isSuccess()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('PUT', 'bar')->shouldBeArray();
    }

    public function it_performs_a_DELETE_request_to_given_url(Client $client, Response $response)
    {
        $client->send(Argument::that(function($arg){
            return $arg instanceof Request && $arg->getMethod() == 'DELETE' && $arg->getUri() == "http://lolcathost/bar";
        }))->shouldBeCalled()->willReturn($response);

        $response->isSuccess()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('DELETE', 'bar')->shouldBeArray();
    }

    public function it_performs_a_POST_request_to_given_url(Client $client, Response $response)
    {
        $file = tempnam(sys_get_temp_dir(), 'veye');

        $client->send(Argument::that(function (Request $arg) use ($file) {
            return $arg->getMethod() == 'POST' &&
                $arg->getUri() == "http://lolcathost/bar" &&
                is_array($arg->getFiles(basename($file)))
            ;
        }))->shouldBeCalled()->willReturn($response);

        $response->isSuccess()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('POST', 'bar', ['foo' => $file])->shouldBeArray();

        @unlink($file);
    }

}
