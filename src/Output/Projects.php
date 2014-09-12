<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Projects
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Projects
{
    /**
     * output for projects API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function all(OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['id','key', 'name', 'type', 'public', 'deps', 'outdated', 'created', 'updated']);

        foreach ($response as $project) {
            $table->addRow([
                $project['id'],
                $project['project_key'],
                $project['name'],
                $project['project_type'],
                $project['public'] ? 'yes' : 'no',
                $project['dep_number'],
                $project['out_number'] > 0 ? '<error>yes</error>' : '<info>no</info>',
                $project['created_at'],
                $project['updated_at']
            ]);
        }

        $table->render();
    }

    /**
     * output for licenses API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function licenses(OutputInterface $output, array $response)
    {
        $table = new Table($output);
        $table->setHeaders(['license', 'name']);

        foreach ($response['licenses'] as $license => $projects) {
            foreach ($projects as $project) {
                $name = $license === 'unknown' ? '<error>'.$project['name'].'</error>' : $project['name'];
                $license = $license === 'unknown' ? '<error>unknown</error>' : $license;

                $table->addRow([$license, $name]);
            }
        }

        $table->render();
    }

    /**
     * output for show API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for update API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function update(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for create API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function create(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for delete API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function delete(OutputInterface $output, array $response)
    {
        if (true == $response['success']) {
            $output->writeln('<info>'.$response['message'].'</info>');
        } else {
            $output->writeln('<error>'.$response['message'].'</error>');
        }
    }

    /**
     * default output for create/show/update
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    private function output(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Name</comment>       : <info>' . $response['name'] . '</info>');
        $output->writeln('<comment>ID</comment>         : <info>' . $response['id'] . '</info>');
        $output->writeln('<comment>Key</comment>        : <info>' . $response['project_key'] . '</info>');
        $output->writeln('<comment>Type</comment>       : <info>' . $response['project_type'] . '</info>');
        $output->writeln('<comment>Public</comment>     : <info>' . ($response['public'] ? 'yes' : 'no') . '</info>');
        $output->writeln('<comment>Outdated</comment>   : '.($response['out_number'] > 0 ? '<error>yes</error>' : '<info>no</info>'));
        $output->writeln('<comment>Created At</comment> : <info>' . $response['created_at'] . '</info>');
        $output->writeln('<comment>Updated At</comment> : <info>' . $response['updated_at']. '</info>');

        $table = new Table($output);

        $table->setHeaders(['name', 'stable', 'outdated', 'current', 'requested']);

        foreach ($response['dependencies'] as $project) {
            $table->addRow([
                $project['name'],
                $project['stable'] ? 'yes' : 'no',
                $project['outdated'] ? '<error>yes</error>' : '<info>no</info>',
                $project['version_current'],
                $project['version_requested']
            ]);
        }

        $table->render();
    }
}
