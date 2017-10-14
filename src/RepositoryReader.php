<?php

namespace GithubReader;


use GithubReader\Github\Directory as GithubRepository;
use GrahamCampbell\GitHub\GitHubManager;

final class RepositoryReader
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

        return new GithubRepository($this);
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