<?php

namespace Rs\VersionEye\Api;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Me Api
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/me
 */
class Me extends BaseApi implements Api
{
    /**
     * shows profile of authorized user
     *
     * @return array
     */
    public function profile()
    {
        return $this->request('me');
    }

    /**
     * shows favorite packages for authorized user
     *
     * @return array
     */
    public function favorites()
    {
        return $this->request('me/favorites');
    }

    /**
     * shows comments of authorized user
     *
     * @return array
     */
    public function comments()
    {
        return $this->request('me/comments');
    }

    /**
     * shows unread notifications of authorized user
     *
     * @return array
     * @todo write output formatter (cant get some notifications)
     */
    public function notifications()
    {
        return $this->request('me/notifications');
    }

    /**
     * output for the profile api
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function profileOutput(InputInterface $input, OutputInterface $output, array $response)
    {
        $output->writeln('<comment>Full Name</comment>     : <info>' . $response['fullname'] . '</info>');
        $output->writeln('<comment>Username</comment>      : <info>' . $response['username'] . '</info>');
        $output->writeln('<comment>E-Mail</comment>        : <info>' . $response['email'] . '</info>');
        $output->writeln('<comment>Admin</comment>         : <info>' . ($response['admin'] ? 'true' : 'false') . '</info>');
        $output->writeln('<comment>Deleted</comment>       : <info>' . ($response['deleted'] ? 'true' : 'false') . '</info>');
        $output->writeln('<comment>Notifications</comment> : <info>' . $response['notifications']['new'] . '/' . $response['notifications']['total'] . '</info>');
    }

    /**
     * output for the favorites api
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function favoritesOutput(InputInterface $input, OutputInterface $output, array $response)
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $response
     */
    public function commentsOutput(InputInterface $input, OutputInterface $output, array $response)
    {
        $table = new Table($output);

        $table->setHeaders(['name', 'language', 'version', 'type', 'date', 'comment']);

        foreach ($response['comments'] as $comment) {
            $table->addRow([$comment['product']['name'], $comment['product']['language'], $comment['product']['version'], $comment['product']['prod_type'], $comment['created_at'], $comment['comment']]);
        }

        $table->render();
    }
}
