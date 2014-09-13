<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Projects
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Projects extends BaseOutput
{
    /**
     * output for projects API
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function all(OutputInterface $output, array $response)
    {
        $this->printTable($output,
            ['Id', 'Key', 'Name', 'Type', 'Public', 'Dependencies', 'Outdated', 'Updated At'],
            ['id', 'project_key', 'name', 'project_type', 'public', 'dep_number', 'out_number', 'updated_at'],
            $response,
            function($key, $value) {
                if ('out_number' !== $key) {
                    return $value;
                }

                return $value > 0 ? '<error>'.$value.'</error>' : '<info>No</info>';
            }
        );
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
        $this->printBoolean($output, $response['message'], $response['message'], true == $response['success']);
    }

    /**
     * default output for create/show/update
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    private function output(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Id', 'Key', 'Type', 'Public', 'Outdated', 'Updated At'],
            ['name', 'id', 'project_key', 'project_type', 'public', 'out_number', 'updated_at'],
            $response,
            function($key, $value) {
                if ('Outdated' !== $key) {
                    return $value;
                }

                return $value > 0 ? '<error>'.$value.'</error>' : '<info>No</info>';
            }
        );

        $this->printTable($output,
            ['Name', 'Stable', 'Outdated', 'Current', 'Requested'],
            ['name', 'stable', 'outdated', 'version_current', 'version_requested'],
            $response['dependencies'],
            function($key, $value) {
                if ('outdated' !== $key) {
                    return $value;
                }

                return $value ? '<error>Yes</error>' : '<info>No</info>';
            }
        );
    }
}
