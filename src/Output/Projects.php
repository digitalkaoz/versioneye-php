<?php

namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Projects.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Projects extends BaseOutput
{
    /**
     * output for projects API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function all(OutputInterface $output, array $response)
    {
        $this->printTable($output,
            ['Key', 'Name', 'Type', 'Public', 'Dependencies', 'Outdated', 'Updated At', 'Bad Licenses', 'Unknown Licenses'],
            ['project_key', 'name', 'project_type', 'public', 'dep_number', 'out_number', 'updated_at', 'licenses_red', 'licenses_unknown'],
            $response,
            function ($key, $value) {
                if ('public' === $key) {
                    return $value === 1 ? 'Yes' : 'No';
                }

                if (!in_array($key, ['out_number', 'licenses_red', 'licenses_unknown'], true)) {
                    return $value;
                }

                return $value > 0 ? '<error>' . $value . '</error>' : '<info>No</info>';
            }
        );
    }

    /**
     * output for licenses API.
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
                $name    = $license === 'unknown' ? '<error>' . $project['name'] . '</error>' : $project['name'];
                $license = $license === 'unknown' ? '<error>unknown</error>' : $license;

                $table->addRow([$license, $name]);
            }
        }

        $table->render();
    }

    /**
     * output for show API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function show(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for update API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function update(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for create API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function create(OutputInterface $output, array $response)
    {
        $this->output($output, $response);
    }

    /**
     * output for delete API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function delete(OutputInterface $output, array $response)
    {
        $this->printMessage($output, $response);
    }

    /**
     * default output for create/show/update.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    private function output(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Name', 'Key', 'Type', 'Public', 'Outdated', 'Updated At', 'Bad Licenses', 'Unknown Licenses'],
            ['name', 'project_key', 'project_type', 'public', 'out_number', 'updated_at', 'licenses_red', 'licenses_unknown'],
            $response,
            function ($key, $value) {
                if (!in_array($key, ['Outdated', 'Bad Licenses', 'Unknown Licenses'], true)) {
                    return $value;
                }

                return $value > 0 ? '<error>' . $value . '</error>' : '<info>No</info>';
            }
        );

        $this->printTable($output,
            ['Name', 'Stable', 'Outdated', 'Current', 'Requested', 'Licenses', 'Vulnerabilities'],
            ['name', 'stable', 'outdated', 'version_current', 'version_requested', 'licenses', 'security_vulnerabilities'],
            $response['dependencies'],
            function ($key, $value) {
                if ('licenses' === $key) {
                    return implode(', ', array_column($value, 'name'));
                }
                if ('stable' === $key) {
                    return !$value ? '<error>No</error>' : '<info>Yes</info>';
                }
                if ('outdated' === $key) {
                    return $value ? '<error>Yes</error>' : '<info>No</info>';
                }

                if ('security_vulnerabilities' === $key) {
                    return count($value) === 0 ? '<info>No</info>' : '<error>' . implode(', ', array_column($value, 'cve')) . '</error>';
                }

                return $value;
            }
        );
    }

    /**
     * output for the merge API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function merge(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', true === $response['success']);
    }

    /**
     * output for the merge_ga API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function mergeGa(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', true === $response['success']);
    }

    /**
     * output for the unmerge API.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function unmerge(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, 'OK', 'FAIL', true === $response['success']);
    }
}
