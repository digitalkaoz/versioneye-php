<?php

namespace Rs\VersionEye\Console;

use phpDocumentor\Reflection\DocBlock;
use Rs\VersionEye\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommandFactory
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class CommandFactory
{
    private $classes = array();

    /**
     * @param array $classes
     */
    public function __construct(array $classes = array())
    {
        $this->classes = $classes ?: array(
            'Rs\VersionEye\Api\Github',
            'Rs\VersionEye\Api\Me',
            'Rs\VersionEye\Api\Products',
            'Rs\VersionEye\Api\Projects',
            'Rs\VersionEye\Api\Services',
            'Rs\VersionEye\Api\Sessions',
            'Rs\VersionEye\Api\Users'
        );
    }

    /**
     * generates Commands from all Api Methods
     *
     * @return array
     */
    public function generateCommands()
    {
        $commands = array();

        foreach ($this->classes as $class) {
            $api = new \ReflectionClass($class);

            foreach ($api->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (strstr($method->getName(), '__')) { //skip magics
                    continue;
                }

                $commands[] = $this->generateCommand($api->getShortName(), $method);
            }
        }

        return $commands;
    }

    /**
     * creates a Command based on an Api Method
     *
     * @param  string            $name
     * @param  \ReflectionMethod $method
     * @return Command
     */
    private function generateCommand($name, \ReflectionMethod $method)
    {
        $command = new Command(strtolower($name.':'.$this->dash($method->getName())));
        $docBlock = new DocBlock($method->getDocComment());

        $command->setDefinition($this->buildDefinition($method));
        $command->setDescription($docBlock->getShortDescription());
        $command->setCode($this->createCode($name, $method));

        return $command;
    }

    /**
     * builds the Input Definition based upon Api Method Parameters
     *
     * @param  \ReflectionMethod $method
     * @return InputDefinition
     */
    private function buildDefinition(\ReflectionMethod $method)
    {
        $definition = new InputDefinition();

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                //option
                $definition->addOption(new InputOption($parameter->getName(), null, InputOption::VALUE_REQUIRED, null, $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null));
            } else {
                //argument
                $definition->addArgument(new InputArgument($parameter->getName(), InputArgument::REQUIRED, null, null));
            }
        }

        $definition->addOption(new InputOption('token', null, InputOption::VALUE_REQUIRED));

        return $definition;
    }

    /**
     * creates the command execution code
     *
     * @param  string            $name
     * @param  \ReflectionMethod $method
     * @return callable
     */
    private function createCode($name, \ReflectionMethod $method)
    {
        return function (InputInterface $input, OutputInterface $output) use ($name, $method) {
            $methodName = $method->getName();

            $client = new Client();

            if ($input->getOption('token')) {
                $client->authorize($input->getOption('token'));
            }

            $api = $client->api(strtolower($name));

            $args = array();

            foreach ($method->getParameters() as $parameter) {
                if ($parameter->isDefaultValueAvailable()) {
                    //option
                    $args[$parameter->getName()] = $input->getOption($parameter->getName());
                } else {
                    //argument
                    $args[$parameter->getName()] = $input->getArgument($parameter->getName());
                }
            }

            $result = call_user_func_array(array($api, $methodName), $args);

            //TODO howto correctly output the given data?
            ladybug_dump_die($result);
        };
    }

    /**
     * dashifies a camelCase string
     *
     * @param  string $name
     * @return string
     */
    private function dash($name)
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1-\\2', '\\1-\\2'), strtr($name, '-', '.')));
    }
}
