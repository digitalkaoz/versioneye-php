<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Rs\VersionEye\Output\Projects;
use Symfony\Component\Console\Output\BufferedOutput;

class ProjectsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Projects::class);
    }

    public function it_prints_a_table_on_all()
    {
        $output = new BufferedOutput();
        $this->all($output, [
            [
                'id'               => '1337',
                'name'             => 'digitalkaoz/versioneye-php',
                'project_type'     => 'composer',
                'public'           => false,
                'dep_number'       => 47,
                'out_number'       => 13,
                'updated_at'       => '25.05.1981',
                'licenses_red'     => 0,
                'licenses_unknown' => 1,
            ],
        ]);

        expect($output->fetch())->toBe(<<<'EOS'
+------+----------------------------+----------+--------+--------------+----------+------------+--------------+------------------+
| Key  | Name                       | Type     | Public | Dependencies | Outdated | Updated At | Bad Licenses | Unknown Licenses |
+------+----------------------------+----------+--------+--------------+----------+------------+--------------+------------------+
| 1337 | digitalkaoz/versioneye-php | composer | No     | 47           | 13       | 25.05.1981 | No           | 1                |
+------+----------------------------+----------+--------+--------------+----------+------------+--------------+------------------+

EOS
        );
    }

    public function it_prints_a_table_on_licenses()
    {
        $output = new BufferedOutput();
        $this->licenses($output, ['licenses' => [
            'MIT' => [[
                'name' => 'digitalkaoz/versioneye-php',
            ]],
            'unknown' => [[
                'name' => 'symfony/symfony',
            ]],
        ]]);

        expect($output->fetch())->toBe(<<<'EOS'
+---------+----------------------------+
| license | name                       |
+---------+----------------------------+
| MIT     | digitalkaoz/versioneye-php |
| unknown | symfony/symfony            |
+---------+----------------------------+

EOS
        );
    }

    public function it_prints_a_list_and_table_on_create()
    {
        $this->defaultOutput('create');
    }

    public function it_prints_a_list_and_table_on_show()
    {
        $this->defaultOutput('show');
    }

    public function it_prints_a_list_and_table_on_update()
    {
        $this->defaultOutput('update');
    }

    public function it_prints_a_boolean_on_delete()
    {
        $output = new BufferedOutput();

        $this->delete($output, ['success' => true, 'message' => 'foo']);

        expect($output->fetch())->toBe(<<<'EOS'
foo

EOS
        );
    }

    public function it_prints_a_boolean_on_merge()
    {
        $output = new BufferedOutput();

        $this->merge($output, ['success' => true]);

        expect($output->fetch())->toBe(<<<'EOS'
OK

EOS
        );
    }

    public function it_prints_a_boolean_on_mergeGa()
    {
        $output = new BufferedOutput();

        $this->mergeGa($output, ['success' => true]);

        expect($output->fetch())->toBe(<<<'EOS'
OK

EOS
        );
    }

    public function it_prints_a_boolean_on_unmerge()
    {
        $output = new BufferedOutput();

        $this->unmerge($output, ['success' => false]);

        expect($output->fetch())->toBe(<<<'EOS'
FAIL

EOS
        );
    }

    private function defaultOutput($method)
    {
        $output = new BufferedOutput();
        $this->{$method}($output, [
            'name'             => 'digitalkaoz/versioneye-php',
            'id'               => '1337',
            'project_type'     => 'composer',
            'public'           => true,
            'out_number'       => 7,
            'updated_at'       => '25.05.1981',
            'licenses_red'     => 7,
            'licenses_unknown' => 8,
            'dependencies'     => [[
                'name'                     => 'symfony/symfony',
                'stable'                   => true,
                'outdated'                 => false,
                'version_current'          => '2.5.0',
                'version_requested'        => '2.5.0',
                'licenses'                 => [['name' => 'MIT']],
                'security_vulnerabilities' => [['cve' => 'lolcat']],
            ]],
        ]);

        expect($output->fetch())->toBe(<<<'EOS'
Name                  : digitalkaoz/versioneye-php
Key                   : 1337
Type                  : composer
Public                : Yes
Outdated              : 7
Updated At            : 25.05.1981
Bad Licenses          : 7
Unknown Licenses      : 8
+-----------------+--------+----------+---------+-----------+----------+-----------------+
| Name            | Stable | Outdated | Current | Requested | Licenses | Vulnerabilities |
+-----------------+--------+----------+---------+-----------+----------+-----------------+
| symfony/symfony | Yes    | No       | 2.5.0   | 2.5.0     | MIT      | lolcat          |
+-----------------+--------+----------+---------+-----------+----------+-----------------+

EOS
        );
    }
}
