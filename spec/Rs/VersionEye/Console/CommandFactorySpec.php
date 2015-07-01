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
        $result->shouldHaveCount(33);
        $result->shouldHaveCommands([
            'github:delete',
            'github:hook',
            'github:import',
            'github:repos',
            'github:show',
            'github:sync',
            'me:comments',
            'me:favorites',
            'me:notifications',
            'me:profile',
            'products:follow',
            'products:follow-status',
            'products:references',
            'products:search',
            'products:show',
            'products:unfollow',
            'products:versions',
            'projects:merge',
            'projects:merge_ga',
            'projects:unmerge',
            'projects:all',
            'projects:create',
            'projects:delete',
            'projects:licenses',
            'projects:show',
            'projects:update',
            'services:ping',
            'sessions:close',
            'sessions:open',
            'sessions:show',
            'users:comments',
            'users:favorites',
            'users:show',
        ]);
        $result->shouldOnlyContainCommandInstances();
    }

    public function it_generates_a_runnable_command()
    {
        $output = new BufferedOutput();
        $result = $this->generateCommands(['Rs\VersionEye\Api\Services']);

        $result->shouldBeArray();
        $result->shouldHaveCount(1);

        $result[0]->shouldHaveType('Symfony\Component\Console\Command\Command');
        $result[0]->getName()->shouldBe('services:ping');
        $result[0]->run(new ArrayInput([]), $output);

        expect($output->fetch())->toBe(<<<EOS
pong

EOS
        );
    }

    public function it_generated_correct_Commands_from_an_Api()
    {
        $result = $this->generateCommands(['spec\Rs\VersionEye\Console\Test']);

        $result->shouldBeArray();
        $result->shouldHaveCount(1);

        $command = $result[0];
        $command->shouldHaveType('Symfony\Component\Console\Command\Command');
        $command->getName()->shouldBe('test:bazz');
        $command->getDescription()->shouldBe('awesome.');

        $command->getDefinition()->hasArgument('foo');

        $command->getDefinition()->hasOption('bar');
        $command->getDefinition()->getOption('bar')->getDefault()->shouldBeNull();

        $command->getDefinition()->hasOption('bazz');
        $command->getDefinition()->getOption('bazz')->getDefault()->shouldBe(1);
    }

    public function getMatchers()
    {
        return [
            'haveCommands' => function ($subject, $keys) {
                $present = [];
                foreach ($subject as $command) {
                    $present[] = $command->getName();
                }

                return [] === array_diff($present, $keys);
            },

            'onlyContainCommandInstances' => function ($subject) {
                foreach ($subject as $command) {
                    if (!$command instanceof Command) {
                        throw new FailureException('"' . get_class($command) . '" is not a subtype of Symfony\Component\Console\Command\Command');
                    }
                }

                return true;
            },
        ];
    }
}

class Test implements Api
{
    /**
     * awesome.
     *
     * @param $foo
     * @param null $bar
     * @param int  $bazz
     *
     * @return array
     */
    public function bazz($foo, $bar = null, $bazz = 1)
    {
        return ['foo' => 'bar'];
    }
}
