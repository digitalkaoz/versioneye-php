<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class ProductsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Products');
    }

    public function it_prints_a_table_on_search()
    {
        $output = new BufferedOutput();
        $this->search($output, ['results' => [
            [
                'name'      => 'digitalkaoz/versioneye-php',
                'language'  => 'php',
                'version'   => '1.0.0',
                'prod_type' => 'composer',
            ],
        ]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+---------+----------+
| Name                       | Language | Version | Type     |
+----------------------------+----------+---------+----------+
| digitalkaoz/versioneye-php | php      | 1.0.0   | composer |
+----------------------------+----------+---------+----------+

EOS
        );
    }

    public function it_prints_a_table_on_versions()
    {
        $output = new BufferedOutput();
        $this->versions($output, [
                'name'      => 'digitalkaoz/versioneye-php',
                'language'  => 'php',
                'version'   => '1.0.0',
                'prod_type' => 'composer',
                'versions'  => [
                    ['version' => '0.1', 'released_at' => '25.05.1981 07:01:00'],
                    ['version' => '0.2', 'released_at' => '25.05.2015 07:01:00'],
                ],
        ]);

        expect($output->fetch())->toBe(<<<EOS
Name          : digitalkaoz/versioneye-php
Language      : php
Key           : 2
Type          : composer
Version       : 1.0.0
+---------+---------------------+
| Version | Released At         |
+---------+---------------------+
| 0.1     | 1981-05-25 07:01:00 |
| 0.2     | 2015-05-25 07:01:00 |
+---------+---------------------+

EOS
        );
    }

    public function it_prints_a_table_on_references()
    {
        $output = new BufferedOutput();
        $this->references($output, ['results' => [
            [
                'name'      => 'digitalkaoz/versioneye-php',
                'language'  => 'php',
                'version'   => '1.0.0',
                'prod_type' => 'composer',
            ],
        ]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+---------+----------+
| Name                       | Language | Version | Type     |
+----------------------------+----------+---------+----------+
| digitalkaoz/versioneye-php | php      | 1.0.0   | composer |
+----------------------------+----------+---------+----------+

EOS
        );
    }

    public function it_prints_a_list_on_show()
    {
        $output = new BufferedOutput();
        $this->show($output, [
            'name'         => 'digitalkaoz/versioneye-php',
            'description'  => 'a php wrapper around the versioneye api',
            'prod_key'     => '1337',
            'prod_type'    => 'composer',
            'license_info' => 'MIT',
            'version'      => '1.0.0',
            'group_id'     => 'foo',
            'updated_at'   => '25.05.1981',
            'links'        => [['name' => 'Source', 'link' => 'http://lolcat.com']],
            'archives'     => [['name' => 'Source', 'link' => 'http://lolcat.com']],
            'dependencies' => [
                ['name' => 'symfony/console', 'parsed_version' => '2.5.3', 'version' => '~2.3'],
            ],
        ]);

        expect($output->fetch())->toBe(<<<EOS
Name             : digitalkaoz/versioneye-php
Description      : a php wrapper around the versioneye api
Source           : http://lolcat.com
Archive          : http://lolcat.com
Key              : 1337
Type             : composer
License          : MIT
Version          : 1.0.0
Group            : foo
Updated At       : 25.05.1981
+-----------------+---------+-----------+
| Name            | Current | Requested |
+-----------------+---------+-----------+
| symfony/console | 2.5.3   | ~2.3      |
+-----------------+---------+-----------+

EOS
        );
    }

    public function it_prints_a_boolean_on_followStatus()
    {
        $output = new BufferedOutput();

        $this->followStatus($output, ['follows' => false]);

        expect($output->fetch())->toBe(<<<EOS
NO

EOS
        );
    }

    public function it_prints_a_boolean_on_follow()
    {
        $output = new BufferedOutput();

        $this->follow($output, ['follows' => true]);

        expect($output->fetch())->toBe(<<<EOS
OK

EOS
        );
    }

    public function it_prints_a_boolean_on_unfollow()
    {
        $output = new BufferedOutput();

        $this->unfollow($output, ['follows' => false]);

        expect($output->fetch())->toBe(<<<EOS
OK

EOS
        );
    }
}
