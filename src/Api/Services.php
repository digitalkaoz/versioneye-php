<?php

namespace Rs\VersionEye\Api;

/**
 * Services API.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/services
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
