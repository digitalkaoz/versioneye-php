<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Me
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Me
{
    /**
     * output for the profile api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function profile(OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Full Name</comment>     : <info>' . $response['fullname'] . '</info>');
        $output->writeln('<comment>Username</comment>      : <info>' . $response['username'] . '</info>');
        $output->writeln('<comment>E-Mail</comment>        : <info>' . $response['email'] . '</info>');
        $output->writeln('<comment>Admin</comment>         : <info>' . ($response['admin'] ? 'yes' : 'no') . '</info>');
        $output->writeln('<comment>Deleted</comment>       : <info>' . ($response['deleted'] ? 'yes' : 'no') . '</info>');
        $output->writeln('<comment>Notifications</comment> : <info>' . $response['notifications']['new'] . '/' . $response['notifications']['total'] . '</info>');
    }

    /**
     * output for the favorites api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function favorites(OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['name', 'language', 'version', 'type']);

        foreach ($response['favorites'] as $favorite) {
            $table->addRow([$favorite['name'], $favorite['language'], $favorite['version'], $favorite['prod_type']]);
        }

        $table->render();
    }

    /**
     * output for the comments api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function comments(OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['name', 'language', 'version', 'type', 'date', 'comment']);

        foreach ($response['comments'] as $comment) {
            $table->addRow([$comment['product']['name'], $comment['product']['language'], $comment['product']['version'], $comment['product']['prod_type'], $comment['created_at'], $comment['comment']]);
        }

        $table->render();
    }
}
