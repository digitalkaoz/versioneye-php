<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Users
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Users extends Me
{
    /**
     * output for the profile API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Full Name</comment>     : <info>' . $response['fullname'] . '</info>');
        $output->writeln('<comment>Username</comment>      : <info>' . $response['username'] . '</info>');
    }
}
