<?php

namespace Rs\VersionEye\Console;

use ArgumentsResolver\NamedArgumentsResolver;
use Camel\CaseTransformerInterface;
use phpDocumentor\Reflection\DocBlock;
use Rs\VersionEye\Authentication\Token;
use Rs\VersionEye\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommandFactory.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class CommandFactory
{
    /**
     * @var CaseTransformerInterface
     */
    private $transformer;

    /**
     * @var Token
     */
    private $token;

    public function __construct(Token $token, CaseTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
        $this->token       = $token;
    }

    /**
     * generates Commands from all Api Methods.
     *
     * @param array $classes
     *
     * @return Command[]
     */
    public function generateCommands(array $classes = [])
    {
        $classes  = $this->readApis($classes);
        $token    = $this->token->read();
        $commands = [];

        foreach ($classes as $class) {
            $api = new \ReflectionClass($class);

            foreach ($api->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if (0 !== strpos($method->getName(), '__')) { //skip magics
                    $command                       = $this->generateCommand($api->getShortName(), $method, $token);
                    $commands[$command->getName()] = $command;
                }
            }
        }

        return $commands;
    }

    /**
     * creates a Command based on an Api Method.
     *
     * @param string            $name
     * @param \ReflectionMethod $method
     * @param string            $token
     *
     * @return Command
     */
    private function generateCommand($name, \ReflectionMethod $method, $token = null)
    {
        $methodName = $this->transformer->transform($method->getName());

        $command  = new Command(strtolower($name . ':' . $methodName));
        $docBlock = new DocBlock($method->getDocComment());

        $command->setDefinition($this->buildDefinition($method, $token));
        $command->setDescription($docBlock->getShortDescription());
        $command->setCode($this->createCode($name, $method));

        return $command;
    }

    /**
     * builds the Input Definition based upon Api Method Parameters.
     *
     * @param \ReflectionMethod $method
     * @param string            $token
     *
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
     * creates the command execution code.
     *
     * @param string            $name
     * @param \ReflectionMethod $method
     *
     * @return \Closure
     */
    private function createCode($name, \ReflectionMethod $method)
    {
        return function (InputInterface $input, OutputInterface $output) use ($name, $method) {
            $client = new Client();

            if ($input->getOption('token')) {
                $client->authorize($input->getOption('token'));
            }

            $methodName  = $method->getName();
            $api         = $client->api(strtolower($name));
            $args        = (new NamedArgumentsResolver($method))->resolve(array_merge($input->getOptions(), $input->getArguments()));
            $outputClass = $this->generateOutputClassFromApiClass($api);

            $response = call_user_func_array([$api, $methodName], $args);

            if (method_exists($outputClass, $methodName)) {
                (new $outputClass())->{$methodName}($output, $response);
            } else {
                $output->writeln(print_r($response, true));
            }
        };
    }

    /**
     * reads all api classes.
     *
     * @param array $classes
     *
     * @return array
     */
    private function readApis(array $classes = [])
    {
        $baseClasses = [
            'Rs\VersionEye\Api\Github',
            'Rs\VersionEye\Api\Me',
            'Rs\VersionEye\Api\Products',
            'Rs\VersionEye\Api\Projects',
            'Rs\VersionEye\Api\Services',
            'Rs\VersionEye\Api\Sessions',
            'Rs\VersionEye\Api\Users',
        ];

        return array_merge($baseClasses, $classes);
    }

    /**
     * remove the last namespace prefix of the Api Class and replace it with Output
     * e.g. Rs\VersionEye\Api\Me -> Rs\VersionEye\Output\Me.
     *
     * @param string $api
     *
     * @return string
     */
    private function generateOutputClassFromApiClass($api)
    {
        $classParts = explode('\\', get_class($api));
        $apiName    = array_pop($classParts);
        array_pop($classParts);

        return implode('\\', $classParts) . '\\Output\\' . $apiName;
    }
}
