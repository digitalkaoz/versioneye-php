<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class UsersSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Users');
    }

    public function it_prints_a_list_on_show()
    {
        $output = new BufferedOutput();
        $this->show($output, [
            'fullname'      => 'Robert Schönthal',
            'username'      => 'digitalkaoz',
            'email'         => 'robert.schoenthal@gmail.com',
        ]);

        expect($output->fetch())->toBe(<<<EOS
Fullname      : Robert Schönthal
Username      : digitalkaoz

EOS
        );
    }

    public function it_prints_a_table_on_favorites()
    {
        $output = new BufferedOutput();
        $this->favorites($output, ['favorites' => [['name' => 'digitalkaoz/versioneye-php', 'language' => 'php', 'version' => '1.0.0', 'prod_type' => 'composer']]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+---------+----------+
| Name                       | Language | Version | Type     |
+----------------------------+----------+---------+----------+
| digitalkaoz/versioneye-php | php      | 1.0.0   | composer |
+----------------------------+----------+---------+----------+

EOS
        );
    }

    public function it_prints_a_table_on_notifications()
    {
        $output = new BufferedOutput();
        $this->notifications($output, ['notifications' => [['name' => 'digitalkaoz/versioneye-php', 'language' => 'php', 'version' => '1.0.0', 'prod_type' => 'composer']]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+---------+----------+
| Name                       | Language | Version | Type     |
+----------------------------+----------+---------+----------+
| digitalkaoz/versioneye-php | php      | 1.0.0   | composer |
+----------------------------+----------+---------+----------+

EOS
        );
    }

    public function it_prints_a_table_on_comments()
    {
        $output = new BufferedOutput();
        $this->comments($output, ['comments' => [
            [
                'created_at' => '25.05.1981',
                'comment' => 'whoohoo',
                'product' => ['name' => 'digitalkaoz/versioneye-php', 'language' => 'php', 'version' => '1.0.0', 'prod_type' => 'composer']
            ]
        ]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+---------+----------+------------+---------+
| Name                       | Language | Version | Type     | Date       | Comment |
+----------------------------+----------+---------+----------+------------+---------+
| digitalkaoz/versioneye-php | php      | 1.0.0   | composer | 25.05.1981 | whoohoo |
+----------------------------+----------+---------+----------+------------+---------+

EOS
        );
    }

}
