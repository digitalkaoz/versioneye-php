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

        $table->setHeaders(['key', 'name', 'type', 'public', 'deps', 'outdated', 'created', 'updated']);

        foreach ($response as $project) {
            $table->addRow([
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
}
