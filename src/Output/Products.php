<?php

namespace Rs\VersionEye\Output;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Products
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Products
{
    /**
     * output for the search API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function search(OutputInterface $output, array $response)
    {
        $this->showList($output, $response);
    }

    /**
     * output for the show API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Name</comment>       : <info>' . $response['name'] . '</info>');
        $output->writeln('<comment>Description</comment>: <info>' . $response['description'] . '</info>');
        $output->writeln('<comment>Key</comment>        : <info>' . $response['prod_key'] . '</info>');
        $output->writeln('<comment>Type</comment>       : <info>' . $response['prod_type'] . '</info>');
        $output->writeln('<comment>License</comment>    : <info>' . $response['license_info'] . '</info>');
        $output->writeln('<comment>Version</comment>    : <info>' . $response['version'] . '</info>');
        $output->writeln('<comment>Group</comment>      : <info>'.$response['group_id'].'</info>');
        $output->writeln('<comment>Updated At</comment> : <info>' . $response['updated_at']. '</info>');
    }

    /**
     * output for the follow status API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function followStatus(OutputInterface $output, array $response)
    {
        if (true == $response['follows']) {
            $output->writeln('<info>YES</info>');
        } else {
            $output->writeln('<error>NO</error>');
        }
    }

    /**
     * output for the follow API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function follow(OutputInterface $output, array $response)
    {
        if (true == $response['follows']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>FAIL</error>');
        }
    }

    /**
     * output for the unfollow API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function unfollow(OutputInterface $output, array $response)
    {
        if (false == $response['follows']) {
            $output->writeln('<info>OK</info>');
        } else {
            $output->writeln('<error>FAIL</error>');
        }
    }

    /**
     * output for the references API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function references(OutputInterface $output, array $response)
    {
        $this->showList($output, $response);
    }

    /**
     * output for search/references
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    private function showList(OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['name', 'language', 'version', 'type']);

        foreach ($response['results'] as $project) {
            $table->addRow([
                $project['name'],
                $project['language'],
                $project['version'],
                $project['prod_type']
            ]);
        }

        $table->render();
    }
}
