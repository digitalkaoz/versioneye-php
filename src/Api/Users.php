<?php

namespace Rs\VersionEye\Api;

/**
 * Users API.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/users
 */
class Users extends BaseApi implements Api
{
    /**
     * shows profile of given user_id.
     *
     * @param string $username
     *
     * @return array
     */
    public function show($username)
    {
        return $this->request('users/' . $username);
    }

    /**
     * shows user's favorite packages.
     *
     * @param string $username
     *
     * @return array
     */
    public function favorites($username)
    {
        return $this->request(sprintf('users/%s/favorites?page=%d', $username, 1));
    }

    /**
     * shows user's comments.
     *
     * @param string $username
     *
     * @return array
     */
    public function comments($username)
    {
        return $this->request(sprintf('users/%s/comments?page=%d', $username, 1));
    }
}
