<?php

namespace spec\Rs\VersionEye\Console;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CommandFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Console\CommandFactory');
    }

    public function it_returns_an_array_of_Commands()
    {
        $result = $this->generateCommands();

        $result->shouldBeArray();
        $result->shouldHaveCount(29);
        $result->shouldOnlyContainCommandInstances();
    }

//TODO wont work on travis cause of certificate foo
//    public function it_generates_a_runnable_command()
//    {
//        $output = new BufferedOutput();
//        $result = $this->generateCommands(['Rs\VersionEye\Api\Users']);
//
//        $result->shouldBeArray();
//        $result->shouldHaveCount(3);
//
//        $result[0]->shouldHaveType('Symfony\Component\Console\Command\Command');
//        $result[0]->getName()->shouldBe('users:show');
//        $result[0]->run(new ArrayInput(['username' => 'digitalkaoz']), $output);
//
//        expect($output->fetch())->toBe(<<<EOS
//Fullname      : Robert Schönthal
//Username      : digitalkaoz
//
//EOS
//        );
//    }

    public function it_generated_correct_Commands_from_an_Api()
    {
        $result = $this->generateCommands(['spec\Rs\VersionEye\Console\Test']);

        $result->shouldBeArray();
        $result->shouldHaveCount(1);

        $result[0]->shouldHaveType('Symfony\Component\Console\Command\Command');
        $result[0]->getName()->shouldBe('test:bazz');
        $result[0]->getDescription()->shouldBe('awesome');

        $result[0]->getDefinition()->hasArgument('foo');

        $result[0]->getDefinition()->hasOption('bar');
        $result[0]->getDefinition()->getOption('bar')->getDefault()->shouldBeNull();

        $result[0]->getDefinition()->hasOption('bazz');
        $result[0]->getDefinition()->getOption('bazz')->getDefault()->shouldBe(1);
    }

    public function getMatchers()
    {
        return [
            'onlyContainCommandInstances' => function ($subject) {
                foreach ($subject as $command) {
                    if (! $command instanceof Command) {
                        throw new FailureException('"'.get_class($command).'" is not a subtype of Symfony\Component\Console\Command\Command');
                    }
                }

                return true;
            }
        ];
    }

}

class Test implements Api
{
    /**
     * awesome
     *
     * @param $foo
     * @param  null  $bar
     * @param  int   $bazz
     * @return array
     */
    public function bazz($foo, $bar = null, $bazz = 1)
    {
        return ['foo' => 'bar'];
    }
}
