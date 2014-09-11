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
    private $classes = [];

    /**
     * @param array $classes
     */
    public function __construct(array $classes = [])
    {
        $this->classes = $classes ?: [
            'Rs\VersionEye\Api\Github',
            'Rs\VersionEye\Api\Me',
            'Rs\VersionEye\Api\Products',
            'Rs\VersionEye\Api\Projects',
            'Rs\VersionEye\Api\Services',
            'Rs\VersionEye\Api\Sessions',
            'Rs\VersionEye\Api\Users'
        ];
    }

    /**
     * generates Commands from all Api Methods
     *
     * @return Command[]
     */
    public function generateCommands()
    {
        $commands = [];
        $token = $this->readConfigurationFile();

        foreach ($this->classes as $class) {
            $api = new \ReflectionClass($class);

            foreach ($api->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (strstr($method->getName(), '__')) { //skip magics
                    continue;
                }

                $commands[] = $this->generateCommand($api->getShortName(), $method, $token);
            }
        }

        return $commands;
    }

    /**
     * creates a Command based on an Api Method
     *
     * @param  string            $name
     * @param  \ReflectionMethod $method
     * @param  string            $token
     * @return Command
     */
    private function generateCommand($name, \ReflectionMethod $method, $token = null)
    {
        $command = new Command(strtolower($name . ':' . $this->dash($method->getName())));
        $docBlock = new DocBlock($method->getDocComment());

        $command->setDefinition($this->buildDefinition($method, $token));
        $command->setDescription($docBlock->getShortDescription());
        $command->setCode($this->createCode($name, $method));

        return $command;
    }

    /**
     * builds the Input Definition based upon Api Method Parameters
     *
     * @param  \ReflectionMethod $method
     * @param  string            $token
     * @return InputDefinition
     */
    private function buildDefinition(\ReflectionMethod $method, $token = null)
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

        $definition->addOption(new InputOption('token', null, InputOption::VALUE_REQUIRED, 'the auth token to use', $token));

        return $definition;
    }

    /**
     * creates the command execution code
     *
     * @param  string            $name
     * @param  \ReflectionMethod $method
     * @return \Closure
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

            $args = [];

            foreach ($method->getParameters() as $parameter) {
                if ($parameter->isDefaultValueAvailable()) {
                    //option
                    $args[$parameter->getName()] = $input->getOption($parameter->getName());
                } else {
                    //argument
                    $args[$parameter->getName()] = $input->getArgument($parameter->getName());
                }
            }

            $result = call_user_func_array([$api, $methodName], $args);

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
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1-\\2', '\\1-\\2'], strtr($name, '-', '.')));
    }

    /**
     * reads global information from the user config file ~/.veye.rc
     *
     * @return string
     */
    private function readConfigurationFile()
    {
        $file = trim(shell_exec('cd ~ && pwd')) . DIRECTORY_SEPARATOR . '.veye.rc';

        if (!file_exists($file)) {
            return;
        }

        $data = file_get_contents($file);
        $data = parse_ini_string(str_replace([': ', ':'], ['= ', ''],  $data)); //stupid convert from .rc to .ini

        if (isset($data['api_key']) && $data['api_key']) {
            return trim($data['api_key']);
        }
    }
}
