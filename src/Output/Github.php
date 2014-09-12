<?php

namespace Rs\VersionEye\Output;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Github
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Github
{
    /**
     * output for sync api
     *
     * @param OutputInterface $output
     * @param array $response
     */
    public function sync(OutputInterface $output, array $response)
    {
        if (true == $response['changed']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<comment>UP TO DATE</comment>');
        }
    }

    /**
     * output for hook api
     *
     * @param OutputInterface $output
     * @param array $response
     */
    public function hook(OutputInterface $output, array $response)
    {
        if (true === $response['success']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>'.$response['success'].'</error>');
        }
    }

    /**
     * output for repos api
     *
     * @param OutputInterface $output
     * @param array $response
     */
    public function repos(OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['name', 'language', 'description', 'owner', 'fork']);

        foreach ($response['repos'] as $project) {
            $table->addRow([
                $project['fullname'],
                $project['language'],
                substr($project['description'],0, 100),
                $project['owner_login'],
                $project['fork'] ? 'yes' : 'no'
            ]);
        }

        $table->render();
    }

    /**
     * output for show api
     *
     * @param OutputInterface $output
     * @param array $response
     */
    public function import(OutputInterface $output, array $response)
    {
        $this->showOutput($output, $response);
    }

    /**
     * output for import api
     *
     * @param OutputInterface $output
     * @param array $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->showOutput($output, $response);
    }

    /**
     * output for import/show
     *
     * @param OutputInterface $output
     * @param array $response
     */
    private function showOutput(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Name</comment>       : <info>' . $response['repo']['fullname'] . '</info>');
        $output->writeln('<comment>Homepage</comment>   : <info>' . $response['repo']['homepage']. '</info>');
        $output->writeln('<comment>Language</comment>   : <info>' . $response['repo']['language'] . '</info>');
        $output->writeln('<comment>Description</comment>: <info>' . $response['repo']['description'] . '</info>');
        $output->writeln('<comment>Public</comment>     : <info>' . ($response['repo']['private'] ? 'yes' : 'no') . '</info>');
        $output->writeln('<comment>Created At</comment> : <info>' . $response['repo']['created_at'] . '</info>');
        $output->writeln('<comment>Http</comment>       : <info>' . $response['repo']['html_url']. '</info>');
        $output->writeln('<comment>Git</comment>        : <info>' . $response['repo']['git_url']. '</info>');
    }
}
