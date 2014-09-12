<?php

namespace spec\Rs\VersionEye\Http;

use Buzz\Browser;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BuzzClientSpec extends ObjectBehavior
{
    public function let(Browser $client)
    {
        $this->beConstructedWith('http://lolcathost/', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Http\BuzzClient');
        $this->shouldHaveType('Rs\VersionEye\Http\HttpClient');
    }

    public function it_performs_a_GET_request_to_given_url(Browser $client, Response $response)
    {
        $client->submit('http://lolcathost/bar', [], 'GET')->shouldBeCalled()->willReturn($response);

        $response->isSuccessful()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('GET', 'bar')->shouldBeArray();
    }

    public function it_performs_a_PUT_request_to_given_url(Browser $client, Response $response)
    {
        $client->submit('http://lolcathost/bar', [], 'PUT')->shouldBeCalled()->willReturn($response);

        $response->isSuccessful()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('PUT', 'bar')->shouldBeArray();
    }

    public function it_performs_a_DELETE_request_to_given_url(Browser $client, Response $response)
    {
        $client->submit('http://lolcathost/bar', [], 'DELETE')->shouldBeCalled()->willReturn($response);

        $response->isSuccessful()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('DELETE', 'bar')->shouldBeArray();
    }

    public function it_performs_a_POST_request_to_given_url(Browser $client, Response $response)
    {
        $file = tempnam(sys_get_temp_dir(), 'veye');

        $client->submit('http://lolcathost/bar', Argument::that(function ($arg) {
            return is_array($arg) && isset($arg['foo']) && $arg['foo'] instanceof FormUpload;
        }), 'POST')->shouldBeCalled()->willReturn($response);

        $response->isSuccessful()->shouldBeCalled()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('[]');

        $this->request('POST', 'bar', ['foo' => $file])->shouldBeArray();

        @unlink($file);
    }

}
