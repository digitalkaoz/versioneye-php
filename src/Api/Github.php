<?php

namespace Rs\VersionEye\Api;

/**
 * Github Api.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/github
 */
class Github extends BaseApi implements Api
{
    /**
     * lists your's github repos.
     *
     * @param string $language
     * @param bool   $private
     * @param string $organization
     * @param string $type
     * @param bool   $imported
     *
     * @return array
     */
    public function repos($language = null, $private = null, $organization = null, $type = null, $imported = null)
    {
        return $this->request(sprintf('github?lang=%s&private=%b&org_name=%s&org_type=%s&page=%d&only_imported=%b',
            $language, $private, $organization, $type, 1, $imported
        ));
    }

    /**
     * re-load github data.
     *
     * @return array
     */
    public function sync()
    {
        return $this->request('github/sync');
    }

    /**
     * shows the detailed information for the repository.
     *
     * @param string $repository
     *
     * @return array
     */
    public function show($repository)
    {
        return $this->request('github/' . $this->transform($repository));
    }

    /**
     * imports project file from github.
     *
     * @param string $repository
     * @param string $branch
     * @param string $file
     *
     * @return array
     */
    public function import($repository, $branch = null, $file = null)
    {
        return $this->request(sprintf('github/%s?branch=%s&file=%s', $this->transform($repository), $branch, $file), 'POST');
    }

    /**
     * remove imported project.
     *
     * @param string $repository
     * @param string $branch
     * @param string $file
     *
     * @return array
     */
    public function delete($repository, $branch = null, $file = null)
    {
        return $this->request(sprintf('github/%s?branch=%s&file=%s', $this->transform($repository), $branch, $file), 'DELETE');
    }

    /**
     * GitHub Hook.
     *
     * @param string $project
     *
     * @return array
     */
    public function hook($project)
    {
        return $this->request(sprintf('github/hook/%s', $project), 'POST');
    }
}
