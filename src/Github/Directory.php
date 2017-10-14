<?php

namespace GithubReader\Github;

use GithubReader\RepositoryReader;

class Directory extends Content
{
    const FILE = 'file';
    const DIRECTORY = 'dir';
    const SYMLINK = 'symlink';

    protected $reader;

    protected $files;

    protected $directories;

    protected $is_index = false;

    /**
     *
     * @param RepositoryReader $reader
     * @param array $readable
     */
    public function __construct(RepositoryReader $reader, array $readable = [])
    {
        parent::__construct($readable);

        $this->reader = $reader;
        $this->files = collect([]);
        $this->directories = collect([]);

        $this->readDirectory();
    }

    public function add(array $readable)
    {
        switch ($readable['type']) {
            case Directory::FILE:
                $this->files->push(new File($readable));
                break;

            case Directory::DIRECTORY:
                $this->directories->push(new Directory($this->reader, $readable));
                break;
        }
    }

    protected function readDirectory()
    {
        $contents = $this->reader->readPath($this->path);

        foreach ($contents as $readable) {
            $this->add($readable);
        }
    }

}