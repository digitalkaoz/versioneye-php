<?php

namespace Rs\VersionEye\Api;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sessions API
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/sessions
 */
class Sessions extends BaseApi implements Api
{
    /**
     * returns session info for authorized users
     *
     * @return array
     */
    public function show()
    {
        return $this->request('sessions');
    }

    /**
     * creates new sessions
     *
     * @return array
     */
    public function open()
    {
        return $this->request('sessions', 'POST');
    }

    /**
     * delete current session aka log out.
     *
     * @return array
     */
    public function close()
    {
        return $this->request('sessions', 'DELETE');
    }

    /**
     * output for show API
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function showOutput(InputInterface $input, OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Full Name</comment> : <info>'.$response['fullname'].'</info>');
        $output->writeln('<comment>API Token</comment> : <info>'.$response['api_key'].'</info>');
    }

    /**
     * output for open API
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function openOutput(InputInterface $input, OutputInterface $output, $response)
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function closeOutput(InputInterface $input, OutputInterface $output, array $response)
    {
        if ('Session is closed now.' == $response['message']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>FAIL</error>');
        }
    }

}
