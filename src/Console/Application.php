<?php

namespace Rs\VersionEye\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * CLI Application
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('versioneye', '@git-version@');

        $this->registerCommands();
    }

    /**
     * Initializes the commands
     */
    private function registerCommands()
    {
        $this->addCommands((new CommandFactory())->generateCommands());
    }
}
