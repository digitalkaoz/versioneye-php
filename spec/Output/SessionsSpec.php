<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class SessionsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Sessions');
    }

    public function it_prints_a_list_on_show()
    {
        $output = new BufferedOutput();
        $this->show($output, [
            'fullname' => 'Robert Schönthal',
            'api_key'  => '1337',
            'email'    => 'robert.schoenthal@gmail.com',
        ]);

        expect($output->fetch())->toBe(<<<'EOS'
Fullname       : Robert Schönthal
API Token      : 1337

EOS
        );
    }

    public function it_prints_a_boolean_on_open()
    {
        $output = new BufferedOutput();

        $this->open($output, 'true');

        expect($output->fetch())->toBe(<<<'EOS'
OK

EOS
        );
    }

    public function it_prints_a_boolean_on_close()
    {
        $output = new BufferedOutput();

        $this->close($output, ['message' => 'Session is closed now.']);

        expect($output->fetch())->toBe(<<<'EOS'
OK

EOS
        );
    }
}
