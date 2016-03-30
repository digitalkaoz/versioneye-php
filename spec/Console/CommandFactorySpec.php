<?php

namespace spec\Rs\VersionEye\Console;

use Camel\CaseTransformer;
use Camel\Format;
use Diff;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Api\Api;
use Rs\VersionEye\Authentication\Token;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CommandFactorySpec extends ObjectBehavior
{
    private static $commands = [
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
        'products:follow_status',
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
    ];

    public function let(Token $token)
    {
        $this->beConstructedWith($token, new CaseTransformer(new Format\CamelCase(), new Format\SnakeCase()));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Console\CommandFactory');
    }

    public function it_returns_an_array_of_Commands()
    {
        $result = $this->generateCommands();

        $result->shouldBeArray();
        $result->shouldHaveCount(count(self::$commands));
        $result->shouldHaveCommands(self::$commands);
        $result->shouldOnlyContainCommandInstances();
    }

    public function it_generates_a_runnable_command()
    {
        $output = new BufferedOutput();
        $result = $this->generateCommands(['Rs\VersionEye\Api\Services']);

        $result->shouldBeArray();
        $result->shouldHaveCount(count(self::$commands));

        $result['services:ping']->shouldHaveType('Symfony\Component\Console\Command\Command');
        $result['services:ping']->getName()->shouldBe('services:ping');
        $result['services:ping']->run(new ArrayInput([]), $output);

        expect($output->fetch())->toBe(<<<'EOS'
pong

EOS
        );
    }

    public function it_generated_correct_Commands_from_an_Api()
    {
        $result = $this->generateCommands(['spec\Rs\VersionEye\Console\Test']);

        $result->shouldBeArray();
        $result->shouldHaveCount(count(self::$commands) + 1);

        $command = $result['test:bazz'];
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

                sort($keys);
                sort($present);

                $diff = new Diff($present, $keys);

                if ($diff->render(new \Diff_Renderer_Text_Unified())) {
                    throw new NotEqualException('command set is not equal to expectation', $keys, $present);
                }

                return true;
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
