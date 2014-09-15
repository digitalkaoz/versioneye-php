<?php

namespace spec\Rs\VersionEye\Console;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class ApplicationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Console\Application');
    }

    public function it_exposes_all_apis()
    {
        $this->all()->shouldHaveCommands([
            'github:delete',
            'github:hook',
            'github:import',
            'github:repos',
            'github:show',
            'github:sync',
            'me:comments',
            'me:favorites',
            'me:notifications',
            'me:profile',
            'products:follow',
            'products:follow-status',
            'products:references',
            'products:search',
            'products:show',
            'products:unfollow',
            'projects:all',
            'projects:create',
            'projects:delete',
            'projects:licenses',
            'projects:show',
            'projects:update',
            'services:ping',
            'sessions:close',
            'sessions:open',
            'sessions:show',
            'users:comments',
            'users:favorites',
            'users:show'
        ]);
    }

    public function getMatchers()
    {
        return [
            'haveCommands' => function ($subject, $keys) {
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $subject)) {
                        throw new FailureException('"'.$key.'" not in command list');
                    }
                }

                return true;
            }
        ];
    }
}
