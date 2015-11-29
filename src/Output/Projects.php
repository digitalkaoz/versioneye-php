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
            function ($key, $value) use ($output) {
                if ('public' === $key) {
                    return $value === 1 ? 'Yes' : 'No';
                }

                if (!in_array($key, ['out_number', 'licenses_red', 'licenses_unknown'], true)) {
                    return $value;
                }

                return $this->printBoolean($output, $value > 0 ? $value : 'No', $value, !$value, false);
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
        $table = $this->createTable($output);
        $table->setHeaders(['license', 'name']);

        foreach ($response['licenses'] as $license => $projects) {
            foreach ($projects as $project) {
                $name    = $license === 'unknown' ? '<error>' . $project['name'] . '</error>' : $project['name'];
                $license = $license === 'unknown' ? '<error>unknown</error>' : $license;

                $table->addRow([$license, $name]);
            }
        }

        $table->render($output);
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
            function ($key, $value) use ($output) {
                if (in_array($key, ['Outdated', 'Bad Licenses', 'Unknown Licenses'], true)) {
                    return $this->printBoolean($output, $value === 0 ? 'No' : $value, $value, !$value, false);
                }

                return $value;
            }
        );

        $this->printTable($output,
            ['Name', 'Stable', 'Outdated', 'Current', 'Requested', 'Licenses', 'Vulnerabilities'],
            ['name', 'stable', 'outdated', 'version_current', 'version_requested', 'licenses', 'security_vulnerabilities'],
            $response['dependencies'],
            function ($key, $value) use ($output) {
                if ('licenses' === $key) {
                    return implode(', ', array_column($value, 'name'));
                }
                if ('stable' === $key) {
                    return $this->printBoolean($output, 'Yes', 'No', $value, false);
                }
                if ('outdated' === $key) {
                    return $this->printBoolean($output, 'No', 'Yes', !$value, false);
                }

                if ('security_vulnerabilities' === $key) {
                    if (is_array($value)) {
                        return $this->printBoolean($output, 'No', implode(', ', array_column($value, 'cve')), count($value) === 0, false);
                    }

                    return $this->printBoolean($output, 'No', 'Yes', true, false);
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
