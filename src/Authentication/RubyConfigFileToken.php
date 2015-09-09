<?php

namespace Rs\VersionEye\Authentication;

/**
 * RubyConfigFileToken.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class RubyConfigFileToken implements Token
{
    private $file;

    /**
     * @param string $file
     */
    public function __construct($file = null)
    {
        if (null === $file) {
            $file = $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.veye.rc';
        }

        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (!is_readable($this->file)) {
            return;
        }

        $data = file_get_contents($this->file);
        $data = parse_ini_string(str_replace([': ', ':'], ['= ', ''], $data)); //stupid convert from .rc to .ini

        if (isset($data['api_key']) && $data['api_key']) {
            return trim($data['api_key']);
        }
    }
}
