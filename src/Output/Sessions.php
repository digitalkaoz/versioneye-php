<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sessions.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Sessions extends BaseOutput
{
    /**
     * output for show API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Fullname', 'API Token'],
            ['fullname', 'api_key'],
            $response
        );
    }

    /**
     * output for open API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function open(OutputInterface $output, $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', 'true' === $response); //response isnt an array oO
    }

    /**
     * output for close API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function close(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', 'Session is closed now.' === $response['message']);
    }
}
