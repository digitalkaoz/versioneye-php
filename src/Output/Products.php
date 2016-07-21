<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Products.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Products extends BaseOutput
{
    /**
     * output for the search API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function search(OutputInterface $output, array $response)
    {
        $this->printProducts($output, $response['results']);
    }

    /**
     * output for the references API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function references(OutputInterface $output, array $response)
    {
        $this->printProducts($output, $response['results']);
    }

    /**
     * output for the show API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Description', 'Source', 'Archive', 'Key', 'Type', 'License', 'Version', 'Group', 'Updated At'],
            ['name', 'description', 'links', 'archives', 'prod_key', 'prod_type', 'license_info', 'version', 'group_id', 'updated_at'],
            $response,
            function ($heading, $value) {
                if ('Source' === $heading) {
                    $value = array_filter($value, function ($link) {
                        return 'Source' === $link['name'];
                    });

                    if (!empty($value)) {
                        return array_pop($value)['link'];
                    }
                }

                if ('Archive' === $heading) {
                    return array_pop($value)['link'];
                }

                return $value;
            }
        );

        $this->printTable($output,
            ['Name', 'Current', 'Requested'],
            ['name', 'parsed_version', 'version'],
            $response['dependencies']
        );
    }

    /**
     * output for the follow status API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function followStatus(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'YES', 'NO', true === $response['follows']);
    }

    /**
     * output for the follow API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function follow(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', true === $response['follows']);
    }

    /**
     * output for the unfollow API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function unfollow(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', false === $response['follows']);
    }

    /**
     * output for the versions API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function versions(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Language', 'Key', 'Type', 'Version'],
            ['name', 'language', 'prod_key', 'prod_type', 'version'],
            $response
        );

        $this->printTable($output,
            ['Version', 'Released At'],
            ['version', 'released_at'],
            $response['versions'],
            function ($key, $value) {
                if ('released_at' !== $key) {
                    return $value;
                }

                return date('Y-m-d H:i:s', strtotime($value));
            }
        );
    }
}
