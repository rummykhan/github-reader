<?php

namespace GithubReader\Github;

use Exception;
use GithubReader\RepositoryReader;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Directory
 *  Since Github itself is a directoy, and each directoy is also a kind of Github repositoy
 *  So here we are considering the same. and recursively reading it.
 *
 * @package GithubReader\Github
 */
final class Directory extends Content implements Arrayable, Jsonable
{
    protected $files;

    protected $directories;

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

    /**
     * Here we are reading the directory itself
     * If directory is index path would be null
     */
    protected function read()
    {
        $contents = $this->reader->readPath($this->path);

        foreach ($contents as $readable) {
            $this->add($readable);
        }
    }

    /**
     * Internal function to add the entity to corresponding collection.
     *
     * @param array $readable
     */
    protected function add(array $readable)
    {
        switch ($readable['type']) {
            case FileType::FILE:
                $this->files->push(new File($this->reader, $readable));
                break;

            case FileType::DIRECTORY:
                $this->directories->push(new Directory($this->reader, $readable));
                break;
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

    /**
     * get all the directories and files.
     *
     * @return \Illuminate\Support\Collection
     */
    public function listAll()
    {
        return $this->getDirectories()->merge($this->getFiles());
    }

    /**
     * retrieve the contents of a directory.
     *
     * @return \Illuminate\Support\Collection
     */
    public function retrieve()
    {
        return $this->listAll();
    }

    /**
     * Find a specific file or directory by key.
     *
     * @param $key
     * @param $name
     * @param bool $all
     * @return \Illuminate\Support\Collection
     */
    public function find($key, $name, $all = true)
    {
        return $this->findFile($key, $name, $all)->merge($this->findDirectory($key, $name, $all));
    }

    /**
     * Find a specific directory or recursively.
     *
     * @param $key
     * @param $name
     * @param bool $all
     * @return \Illuminate\Support\Collection
     */
    public function findDirectory($key, $name, $all = false)
    {
        if (! $all) {
            return $this->getDirectories()->where($key, $name);
        }

        $found = $this->getDirectories()->where($key, $name);
        foreach ($this->getDirectories() as $directory) {
            $found->merge($directory->findDirectory($key, $name, $all));
        }

        return $found;
    }

    /**
     * Find a file inside a directory or recursively.
     *
     * @param $key
     * @param $name
     * @param bool $all
     * @return \Illuminate\Support\Collection
     */
    public function findFile($key, $name, $all = false)
    {
        if (! $all) {
            return $this->getFiles()->where($key, $name);
        }

        $found = $this->getFiles()->where($key, $name);
        foreach ($this->getDirectories() as $directory) {
            $found->merge($directory->findFile($key, $name, $all));
        }

        return $found;
    }

    /**
     * Convert the object to its Array representation
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->listAll()->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return $this->listAll()->toJson($options);
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        if (str_contains($name, 'InDirectories') && method_exists(collect(), str_replace('InDirectories', '', $name))) {
            return call_user_func_array([$this->getDirectories(), str_replace('InDirectories', '', $name)], $arguments);
        }

        if (str_contains($name, 'InFiles') && method_exists(collect(), str_replace('InFiles', '', $name))) {
            return call_user_func_array([$this->getFiles(), str_replace('InFiles', '', $name)], $arguments);
        }

        throw new Exception('Method not available.');
    }
}