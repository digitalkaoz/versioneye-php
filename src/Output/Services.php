<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Services
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Services
{
    /**
     * output for the ping api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function ping(OutputInterface $output, array $response)
    {
        if (true == $response['success']) {
            $output->writeln('<info>'.$response['message'].'</info>');
        } else {
            $output->writeln('<error>'.$response['message'].'</error>');
        }
    }

}
