<?php

namespace Rs\VersionEye\Api;

/**
 * Sessions API.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/sessions
 */
class Sessions extends BaseApi implements Api
{
    /**
     * returns session info for authorized users.
     *
     * @return array
     */
    public function show()
    {
        return $this->request('sessions');
    }

    /**
     * creates new sessions.
     *
     * @return array
     */
    public function open()
    {
        return $this->request('sessions', 'POST');
    }

    /**
     * delete current session aka log out.
     *
     * @return array
     */
    public function close()
    {
        return $this->request('sessions', 'DELETE');
    }
}
