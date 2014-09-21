<?php

namespace spec\Rs\VersionEye\Http;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Http\HttpClient;

class PagerSpec extends ObjectBehavior
{
    public function let(HttpClient $client)
    {
        $this->beConstructedWith(
            array('repos'=>['foo','bar'], 'paging'=>['current_page'=>1, 'total_entries' => 4]),
            'repos',
            $client,
            'GET',
            '/foo?page=1'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Http\Pager');
        $this->shouldHaveType('\Iterator');
    }

    public function it_fetches_next_pages_until_all_are_results_are_fetched(HttpClient $client)
    {
        $client->request('GET', '/foo?page=2', [])->shouldBeCalled()->willReturn(array('repos'=>['bazz','lol'], 'paging'=>['current_page'=>2, 'total_entries' => 4]));

        $this->current()->shouldBe('foo');
        $this->next();
        $this->current()->shouldBe('bar');
        $this->key()->shouldBe(1);
        $this->valid()->shouldBe(true);
        $this->next();
        $this->valid()->shouldBe(true);
        $this->current()->shouldBe('bazz');
        $this->next();
        $this->current()->shouldBe('lol');
        $this->next();
        $this->valid()->shouldBe(false);
    }
}
