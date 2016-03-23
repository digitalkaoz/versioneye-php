<?php

namespace Rs\VersionEye\Output;

use Rs\VersionEye\Http\Pager;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BaseOutput.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
abstract class BaseOutput
{
    /**
     * prints a table, values can be modified via $callback.
     *
     * @param OutputInterface $output
     * @param string[]        $headings
     * @param string[]        $keys
     * @param array|Pager     $data
     * @param \Closure        $callback
     */
    protected function printTable(OutputInterface $output, array $headings, array $keys, $data, \Closure $callback = null)
    {
        $table = $this->createTable($output);

        $table->setHeaders(array_map('trim', $headings));

        foreach ($data as $row) {
            $rowData = array_merge(array_flip($keys), array_intersect_key($row, array_flip($keys)));
            if ($callback) {
                $rowData = array_map($callback, array_keys($rowData), $rowData);
            }
            $table->addRow(array_map('trim', $rowData));
        }

        $table->render($output);
    }

    /**
     * prints a simple boolean.
     *
     * @param OutputInterface $output
     * @param string          $success
     * @param string          $fail
     * @param bool            $value
     * @param bool            $line
     *
     * @return string
     */
    protected function printBoolean(OutputInterface $output, $success, $fail, $value, $line = true)
    {
        if ($value) {
            $message = '<info>'.$success.'</info>';
        } else {
            $message = '<error>'.$fail.'</error>';
        }

        if (false === $line) {
            return $message;
        }

        $output->writeln($message);
    }

    /**
     * prints a list combined as <comment>Heading</comment> : <info>Value</info>, values can be modified via $callback.
     *
     * @param OutputInterface $output
     * @param string[]        $headings
     * @param string[]        $keys
     * @param array           $data
     * @param \Closure        $callback
     */
    protected function printList(OutputInterface $output, array $headings, array $keys, array $data, \Closure $callback = null)
    {
        $width = $this->getColumnWidth($headings);
        $data = array_merge(array_flip($keys), array_intersect_key($data, array_flip($keys)));

        foreach ($headings as $key => $heading) {
            $value = array_values($data)[$key];
            if ($callback) {
                $value = $callback($heading, $value);
            }
            $value = is_bool($value) ? (true === $value ? 'Yes' : 'No') : $value;

            $output->writeln(sprintf('<comment>%s%s</comment> : <info>%s</info>', $heading, str_repeat(' ', $width - strlen($heading)), $value));
        }
    }

    /**
     * output for references/search api.
     *
     * @param OutputInterface $output
     * @param array|Pager     $products
     */
    protected function printProducts(OutputInterface $output, $products)
    {
        $this->printTable($output,
            ['Name', 'Language', 'Version', 'Type'],
            ['name', 'language', 'version', 'prod_type'],
            $products
        );
    }

    /**
     * prints a simple message.
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    protected function printMessage(OutputInterface $output, array $response)
    {
        $this->printBoolean($output, $response['message'], $response['message'], true === $response['success']);
    }

    /**
     * calculates the max width of a given set of string.
     *
     * @param string[] $headings
     *
     * @return int
     */
    private function getColumnWidth(array $headings)
    {
        $width = 0;
        foreach ($headings as $heading) {
            $width = strlen($heading) > $width ? strlen($heading) : $width;
        }

        return $width + 5;
    }

    /**
     * @param OutputInterface $output
     *
     * @return Table|TableHelper
     */
    protected function createTable(OutputInterface $output)
    {
        if (!class_exists('Symfony\Component\Console\Helper\Table')) {
            $table = new TableHelper(false);
        } else {
            $table = new Table($output);
        }

        return $table;
    }
}
