<?php

namespace Rs\VersionEye\Api;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Services API
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/services
 */
class Services extends BaseApi implements Api
{
    /**
     * Answers to request with basic pong.
     *
     * @return array
     */
    public function ping()
    {
        return $this->request('services/ping');
    }

    /**
     * output for the ping api
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $response
     */
    public function pingOutput(InputInterface $input, OutputInterface $output, array $response)
    {
        if (true == $response['success']) {
            $output->writeln('<info>'.$response['message'].'</info>');
        } else {
            $output->writeln('<error>'.$response['message'].'</error>');
        }
    }
}
