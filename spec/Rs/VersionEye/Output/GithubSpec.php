<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class GithubSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Github');
    }

    public function it_prints_a_boolean_on_sync()
    {
        $output = new BufferedOutput();

        $this->sync($output, ['status' => 'done']);

        expect($output->fetch())->toBe(<<<EOS
OK

EOS
        );
    }

    public function it_prints_a_boolean_on_hook()
    {
        $output = new BufferedOutput();

        $this->hook($output, ['success' => 'failure']);

        expect($output->fetch())->toBe(<<<EOS
failure

EOS
        );
    }

    public function it_prints_a_table_on_repos()
    {
        $output = new BufferedOutput();
        $this->repos($output, ['repos' => [['fullname' => 'digitalkaoz/versioneye-php', 'language' => 'php', 'description' => 'wrapper around versioneye api', 'owner_login' => 'digitalkaoz', 'fork' => false, 'foo' => 'bazz']]]);

        expect($output->fetch())->toBe(<<<EOS
+----------------------------+----------+-------------------------------+-------------+------+
| Name                       | Language | Description                   | Owner       | Fork |
+----------------------------+----------+-------------------------------+-------------+------+
| digitalkaoz/versioneye-php | php      | wrapper around versioneye api | digitalkaoz |      |
+----------------------------+----------+-------------------------------+-------------+------+

EOS
        );
    }

    public function it_prints_a_list_on_import()
    {
        $output = new BufferedOutput();
        $this->import($output, ['repo' => [
            'fullname'    => 'digitalkaoz/versioneye-php',
            'homepage'    => 'http://digitalkaoz.github.io/versioneye-php',
            'language'    => 'php',
            'description' => 'wrapper around versioneye api',
            'private'     => false,
            'created_at'  => '25.05.1981',
            'html_url'    => 'https://github.com/digitalkaoz/versioneye-php',
            'git_url'     => 'git@github.com:digitalkaoz/versioneye-php.git',
        ]]);

        expect($output->fetch())->toBe(<<<EOS
Name             : digitalkaoz/versioneye-php
Homepage         : http://digitalkaoz.github.io/versioneye-php
Language         : php
Description      : wrapper around versioneye api
Public           : No
Created At       : 25.05.1981
Http             : https://github.com/digitalkaoz/versioneye-php
Git              : git@github.com:digitalkaoz/versioneye-php.git

EOS
        );
    }

    public function it_prints_a_list_on_show()
    {
        $output = new BufferedOutput();
        $this->show($output, ['repo' => [
            'fullname'    => 'digitalkaoz/versioneye-php',
            'homepage'    => 'http://digitalkaoz.github.io/versioneye-php',
            'language'    => 'php',
            'description' => 'wrapper around versioneye api',
            'private'     => false,
            'created_at'  => '25.05.1981',
            'html_url'    => 'https://github.com/digitalkaoz/versioneye-php',
            'git_url'     => 'git@github.com:digitalkaoz/versioneye-php.git',
        ]]);

        expect($output->fetch())->toBe(<<<EOS
Name             : digitalkaoz/versioneye-php
Homepage         : http://digitalkaoz.github.io/versioneye-php
Language         : php
Description      : wrapper around versioneye api
Public           : No
Created At       : 25.05.1981
Http             : https://github.com/digitalkaoz/versioneye-php
Git              : git@github.com:digitalkaoz/versioneye-php.git

EOS
        );
    }
}
