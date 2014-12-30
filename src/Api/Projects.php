<?php

namespace Rs\VersionEye\Api;

/**
 * Projects API
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/projects
 */
class Projects extends BaseApi implements Api
{
    /**
     * shows user`s projects
     *
     * @return array
     */
    public function all()
    {
        return $this->request('projects');
    }

    /**
     * shows the project's information
     *
     * @param  string $project
     * @return array
     */
    public function show($project)
    {
        return $this->request('projects/'.$project);
    }

    /**
     * delete given project
     *
     * @param  string $project
     * @return array
     */
    public function delete($project)
    {
        return $this->request('projects/'.$project, 'DELETE');
    }

    /**
     * upload project file
     *
     * @param  string $file
     * @return array
     */
    public function create($file)
    {
        return $this->request('projects', 'POST', ['upload' => $file]);
    }

    /**
     * update project with new file
     *
     * @param  string $project
     * @param  string $file
     * @return array
     */
    public function update($project, $file)
    {
        return $this->request('projects/'.$project, 'POST', ['project_file' => $file]);
    }

    /**
     * get grouped view of licences for dependencies
     *
     * @param  string $project
     * @return array
     */
    public function licenses($project)
    {
        return $this->request(sprintf('projects/%s/licenses', $project));
    }
}
