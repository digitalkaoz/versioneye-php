<?php

namespace spec\Rs\VersionEye\Authentication;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Authentication\RubyConfigFileToken;
use Rs\VersionEye\Authentication\Token;

class RubyConfigFileTokenSpec extends ObjectBehavior
{
    private $file;

    public function let()
    {
        $this->file = tempnam(sys_get_temp_dir(), 'veye');
        file_put_contents($this->file, ':api_key: lolcat');

        $this->beConstructedWith($this->file);
    }

    public function go()
    {
        unlink($this->file);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RubyConfigFileToken::class);
        $this->shouldHaveType(Token::class);
    }

    public function it_reads_the_token_from_a_file()
    {
        $this->read()->shouldBe('lolcat');
    }

    public function it_returns_empty_token_if_file_doesnt_exists()
    {
        $this->beConstructedWith('foo');

        $this->read()->shouldBe(null);
    }
}
