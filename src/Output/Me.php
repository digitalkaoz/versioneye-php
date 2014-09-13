<?php


namespace Rs\VersionEye\Output;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Me
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Me extends BaseOutput
{
    /**
     * output for the profile api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function profile(OutputInterface $output, array $response)
    {
        $this->printList($output,
            ['Fullname', 'Username', 'Email', 'Admin', 'Notifications'],
            ['fullname', 'username', 'email', 'admin', 'notifications'],
            $response,
            function ($key, $value) {
                if ('Notifications' !== $key) {
                    return $value;
                }

                return sprintf('%d / %d', $value['new'], $value['total']);
            }
        );
    }

    /**
     * output for the favorites api
     *
     * @param OutputInterface $output
     * @param array           $response
     */
    public function favorites(OutputInterface $output, array $response)
    {
        $this->printProducts($output, $response['favorites']);
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

        $table->setHeaders(['Name', 'Language', 'Version', 'Type', 'Date', 'Comment']);

        foreach ($response['comments'] as $comment) {
            $table->addRow([$comment['product']['name'], $comment['product']['language'], $comment['product']['version'], $comment['product']['prod_type'], $comment['created_at'], $comment['comment']]);
        }

        $table->render();
    }
}
