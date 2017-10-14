<?php

namespace GithubReader;


use GithubReader\Github\Directory;
use GrahamCampbell\GitHub\GitHubManager;

class RepositoryReader
{
    protected $manager;
    protected $organization;
    protected $repository;

    /**
     * GithubReader constructor.
     * @param GitHubManager $manager
     */
    public function __construct(GitHubManager $manager)
    {
        $this->manager = $manager;
    }

    public function read($organization, $repository)
    {
        $this->organization = $organization;
        $this->repository = $repository;

        return new Directory($this);
    }

    public function readPath($path = null)
    {
        return $this->manager
            ->connection('app')
            ->api('repo')
            ->contents()
            ->show($this->organization, $this->repository, $path);
    }
}