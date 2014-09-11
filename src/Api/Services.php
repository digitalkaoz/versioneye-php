<?php

namespace Rs\VersionEye\Api;

use GuzzleHttp\Client;

/**
 * ServicesApi
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Services extends BaseApi implements Api
{
    /**
     * Answers to request with basic pong.
     *
     * @return array
     */
    public function ping()
    {
        return $this->request('services/ping');
    }
}
