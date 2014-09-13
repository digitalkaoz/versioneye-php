<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Services
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Services extends BaseOutput
{
    /**
     * output for the ping api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function ping(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, $response['message'], $response['message'], true == $response['success']);
    }
}
