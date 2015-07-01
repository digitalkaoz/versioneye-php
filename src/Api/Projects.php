<?php

namespace Rs\VersionEye\Api;

/**
 * Projects API.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 *
 * @see https://www.versioneye.com/api/v2/swagger_doc/projects
 */
class Projects extends BaseApi implements Api
{
    /**
     * shows user`s projects.
     *
     * @return array
     */
    public function all()
    {
        return $this->request('projects');
    }

    /**
     * shows the project's information.
     *
     * @param string $project
     *
     * @return array
     */
    public function show($project)
    {
        return $this->request('projects/' . $project);
    }

    /**
     * delete given project.
     *
     * @param string $project
     *
     * @return array
     */
    public function delete($project)
    {
        return $this->request('projects/' . $project, 'DELETE');
    }

    /**
     * upload project file.
     *
     * @param string $file
     *
     * @return array
     */
    public function create($file)
    {
        return $this->request('projects', 'POST', ['upload' => $file]);
    }

    /**
     * update project with new file.
     *
     * @param string $project
     * @param string $file
     *
     * @return array
     */
    public function update($project, $file)
    {
        return $this->request('projects/' . $project, 'POST', ['project_file' => $file]);
    }

    /**
     * get grouped view of licences for dependencies.
     *
     * @param string $project
     *
     * @return array
     */
    public function licenses($project)
    {
        return $this->request(sprintf('projects/%s/licenses', $project));
    }

    /**
     * merge two projects together.
     *
     * @param string $parent
     * @param string $child
     *
     * @return array
     */
    public function merge($parent, $child)
    {
        return $this->request(sprintf('projects/%s/merge/%s', $parent, $child));
    }

    /**
     * unmerge two projects.
     *
     * @param string $parent
     * @param string $child
     *
     * @return array
     */
    public function unmerge($parent, $child)
    {
        return $this->request(sprintf('projects/%s/unmerge/%s', $parent, $child));
    }

    /**
     * merge two projects together (only for maven projects).
     *
     * @param string $group
     * @param string $artifact
     * @param string $child
     *
     * @return array
     */
    public function merge_ga($group, $artifact, $child)
    {
        return $this->request(sprintf('projects/%s/%s/merge_ga/%s', $group, $artifact, $child));
    }
}
