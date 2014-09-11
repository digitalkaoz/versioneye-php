<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sessions
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Sessions
{
    /**
     * output for show API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Full Name</comment> : <info>'.$response['fullname'].'</info>');
        $output->writeln('<comment>API Token</comment> : <info>'.$response['api_key'].'</info>');
    }

    /**
     * output for open API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function open(OutputInterface $output, $response)
    {
        if ('true' == $response) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>FAIL</error>');
        }
    }

    /**
     * output for close API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function close(OutputInterface $output, array $response)
    {
        if ('Session is closed now.' == $response['message']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>FAIL</error>');
        }
    }
}
