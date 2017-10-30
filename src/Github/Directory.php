<?php

namespace GithubReader\Github;

use Exception;
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

    public function listAll()
    {
        return $this->getDirectories()->merge($this->getFiles());
    }

    public function navigate()
    {

    }

    public function find($key, $name, $type = Directory::FILE, $all = true)
    {
        switch ($type) {
            case Directory::FILE:
                return $this->findFile($key, $name, $all);
                break;

            case Directory::DIRECTORY:
                return $this->findDirectory($key, $name, $all);
                break;
        }

        throw new Exception('This file type is not supported.');
    }

    public function findDirectory($key, $name, $all = false)
    {
        if (!$all) {
            return $this->getDirectories()->where($key, $name);
        }

        $found = $this->getDirectories()->where($key, $name);
        foreach ($this->getDirectories() as $directory) {
            $found->merge($directory->findDirectory($key, $name, $all));
        }

        return $found;
    }

    public function findFile($key, $name, $all = false)
    {
        if (!$all) {
            return $this->getFiles()->where($key, $name);
        }

        $found = $this->getFiles()->where($key, $name);
        foreach ($this->getDirectories() as $directory) {
            $found->merge($directory->findFile($key, $name, $all));
        }

        return $found;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        if (str_contains($name, 'InDirectories') &&
            method_exists(collect(), str_replace('InDirectories', '', $name))
        ) {
            return call_user_func_array([$this->getDirectories(), str_replace('InDirectories', '', $name)], $arguments);
        }

        if (str_contains($name, 'InFiles') &&
            method_exists(collect(), str_replace('InFiles', '', $name))
        ) {
            return call_user_func_array([$this->getFiles(), str_replace('InFiles', '', $name)], $arguments);
        }

        throw new Exception('Method not available.');
    }

}