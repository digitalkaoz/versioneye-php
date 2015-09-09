<?php

namespace Rs\VersionEye\Authentication;

/**
 * Token.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Token
{
    /**
     * reads the versioneye auth token.
     *
     * @return string
     */
    public function read();
}
