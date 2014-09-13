<?php

namespace Rs\VersionEye\Output;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Github
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Github extends BaseOutput
{
    /**
     * output for sync api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function sync(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'UP TO DATE', true == $response['changed']);
    }

    /**
     * output for hook api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function hook(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', $response['success'], true == $response['success']);
    }

    /**
     * output for repos api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function repos(OutputInterface $output, array $response)
    {
        $this->printTable($output,
            ['Name', 'Language', 'Description', 'Owner', 'Fork'],
            ['fullname', 'language', 'description', 'owner_login', 'fork'],
            $response['repos']
        );
    }

    /**
     * output for show api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function import(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Homepage', 'Language', 'Description', 'Public', 'Created At', 'Http', 'Git'],
            ['fullname', 'homepage', 'language', 'description', 'private', 'created_at', 'html_url', 'git_url'],
            $response['repo']
        );
    }

    /**
     * output for import api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Homepage', 'Language', 'Description', 'Public', 'Created At', 'Http', 'Git'],
            ['fullname', 'homepage', 'language', 'description', 'private', 'created_at', 'html_url', 'git_url'],
            $response['repo']
        );
    }
}
