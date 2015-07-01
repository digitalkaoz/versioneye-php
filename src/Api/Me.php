<?php

namespace Rs\VersionEye\Api;

/**
 * Me Api.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/me
 */
class Me extends BaseApi implements Api
{
    /**
     * shows profile of authorized user.
     *
     * @return array
     */
    public function profile()
    {
        return $this->request('me');
    }

    /**
     * shows favorite packages for authorized user.
     *
     * @return array
     */
    public function favorites()
    {
        return $this->request('me/favorites');
    }

    /**
     * shows comments of authorized user.
     *
     * @return array
     */
    public function comments()
    {
        return $this->request('me/comments');
    }

    /**
     * shows unread notifications of authorized user.
     *
     * @return array
     */
    public function notifications()
    {
        return $this->request('me/notifications');
    }
}
