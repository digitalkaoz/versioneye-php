<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * BaseOutput
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
abstract class BaseOutput
{
    protected function printTable(OutputInterface $output, array $headings, array $keys, array $data, \Closure $callback = null)
    {
        $table = new Table($output);

        $table->setHeaders($headings);

        foreach ($data as $row) {
            $rowData = array_merge(array_flip($keys), array_intersect_key($row, array_flip($keys)));
            if ($callback) {
                $rowData = array_map($callback, array_keys($rowData), $rowData);
            }
            $table->addRow($rowData);
        }

        $table->render();
    }

    protected function printBoolean(OutputInterface $output, $success, $fail, $value)
    {
        if ($value) {
            $output->writeln('<info>'.$success.'</info>');
        } else {
            $output->writeln('<error>'.$fail.'</error>');
        }
    }

    protected function printList(OutputInterface $output, array $headings, array $keys, array $data, \Closure $callback = null)
    {
        $width = $this->getColumnWidth($headings) + 5;
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
     * @param array $headings
     *
     * @return int
     */
    private function getColumnWidth(array $headings)
    {
        $width = 0;
        foreach ($headings as $heading) {
            $width = strlen($heading) > $width ? strlen($heading) : $width;
        }

        return $width + 2;
    }

} 