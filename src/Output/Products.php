<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Products
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Products extends BaseOutput
{
    /**
     * output for the search API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function search(OutputInterface $output, array $response)
    {
        $this->printProducts($output, $response['results']);
    }

    /**
     * output for the references API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function references(OutputInterface $output, array $response)
    {
        $this->printProducts($output, $response['results']);
    }

    /**
     * output for the show API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Description', 'Key', 'Type', 'License', 'Version', 'Group', 'Updated At'],
            ['name', 'description', 'prod_key', 'prod_type', 'license_info', 'version', 'group_id', 'updated_at'],
            $response
        );

        $this->printTable($output,
            ['Name', 'Current', 'Requested'],
            ['name', 'parsed_version', 'version'],
            $response['dependencies']
        );
    }

    /**
     * output for the follow status API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function followStatus(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'YES', 'NO', true === $response['follows']);
    }

    /**
     * output for the follow API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function follow(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', true === $response['follows']);
    }

    /**
     * output for the unfollow API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function unfollow(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', false === $response['follows']);
    }
}
