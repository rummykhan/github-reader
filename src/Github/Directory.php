<?php

namespace GithubReader\Github;

use GithubReader\RepositoryReader;

final class Directory extends Content
{
    const FILE = 'file';
    const DIRECTORY = 'dir';
    const SYMLINK = 'symlink';

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
        parent::__construct($reader, $readable);

        $this->files = collect([]);
        $this->directories = collect([]);

        $this->read();
    }

    public function add(array $readable)
    {
        switch ($readable['type']) {
            case Directory::FILE:
                $this->files->push(new File($this->reader, $readable));
                break;

            case Directory::DIRECTORY:
                $this->directories->push(new Directory($this->reader, $readable));
                break;
        }
    }

    protected function read()
    {
        $contents = $this->reader->readPath($this->path);

        foreach ($contents as $readable) {
            $this->add($readable);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFiles(): \Illuminate\Support\Collection
    {
        return $this->files;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDirectories(): \Illuminate\Support\Collection
    {
        return $this->directories;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        collect();
    }

}