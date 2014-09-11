<?php

namespace Rs\VersionEye\Api;

/**
 * Github Api
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/github
 */
class Github extends BaseApi implements Api
{
    /**
     * lists your's github repos
     *
     * @param  string $language
     * @param  bool   $private
     * @param  string $organization
     * @param  string $type
     * @param  int    $page
     * @param  bool   $imported
     * @return array
     */
    public function repos($language = null, $private = null, $organization = null, $type = null, $page = null, $imported = null)
    {
        return $this->request(sprintf('github?lang=%s&private=%b&org_name=%s&org_type=%s&page=%d&only_imported=%b',
            $language, $private, $organization, $type, $page, $imported
        ));
    }

    /**
     * re-load github data
     *
     * @param  bool  $force
     * @return array
     */
    public function sync($force = null)
    {
        return $this->request(sprintf('github/sync?sync=%b', $force));
    }

    /**
     * search github repositories on github
     *
     * @param  string $query
     * @param  string $languages
     * @param  string $users
     * @param  int    $page
     * @return array
     */
    public function search($query, $languages = null, $users = null, $page = null)
    {
        return $this->request(sprintf('github/search?q=%s&langs=%s&users=%s&page=%d',
            $query, $languages, $users, $page
        ));
    }

    /**
     * shows the detailed information for the repository
     *
     * @param  string $repository
     * @return array
     */
    public function show($repository)
    {
        return $this->request('github/'.$repository);
    }

    /**
     * imports project file from github
     *
     * @param  string $repository
     * @param  string $branch
     * @return array
     */
    public function import($repository, $branch = null)
    {
        return $this->request(sprintf('github/%s?branch=%s', $this->transform($repository), $branch), 'POST');
    }

    /**
     * remove imported project
     *
     * @param  string $repository
     * @param  string $branch
     * @return array
     */
    public function delete($repository, $branch = null)
    {
        return $this->request(sprintf('github/%s?branch=%s', $this->transform($repository), $branch), 'DELETE');
    }

    /**
     * GitHub Hook
     *
     * @param  string $project
     * @return array
     */
    public function hook($project)
    {
        return $this->request(sprintf('github/hook/%s', $project), 'POST');
    }

}
