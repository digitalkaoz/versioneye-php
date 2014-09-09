<?php

namespace Rs\VersionEye\Console;

use phpDocumentor\Reflection\DocBlock;
use Rs\VersionEye\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommandFactory
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class CommandFactory
{
    private $classes = array();

    public function __construct(array $classes = array())
    {
        $this->classes = $classes ?: array(
            'Rs\VersionEye\Api\Services',
            'Rs\VersionEye\Api\Products'
        );
    }
    public function generateCommands()
    {
        $commands = array();

        foreach ($this->classes as $api) {
            $reflection = new \ReflectionClass($api);
            $name = $reflection->getShortName();

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (strstr($method->getName(), '__')) {
                    continue;
                }

                $commands[] = $this->generateCommand($name, $method);
            }
        }

        return $commands;
    }

    private function generateCommand($name, \ReflectionMethod $method)
    {
        $command = new Command(strtolower($name.':'.$this->dash($method->getName())));
        $docBlock = new DocBlock($method->getDocComment());

        $definition = array();
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                //option
                $arg = new InputOption($parameter->getName(),null ,InputOption::VALUE_REQUIRED, null, $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            } else {
                //argument
                $arg = new InputArgument($parameter->getName(), InputArgument::REQUIRED, null, null);
            }

            $definition[] = $arg;
        }

        $command->setDefinition($definition);
        $command->setDescription($docBlock->getShortDescription());

        $command->setCode(function (InputInterface $input, OutputInterface $output) use ($name, $method) {
            $methodName = $method->getName();

            $api = (new Client())->api(strtolower($name));

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
        });

        return $command;
    }

    private function dash($name)
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1-\\2', '\\1-\\2'), strtr($name, '-', '.')));
    }

}
