<?php

namespace GithubReader\Github;


use GithubReader\RepositoryReader;
use Illuminate\Support\Str;

abstract class Content
{
    protected $reader;
    protected $name;
    protected $path = null;
    protected $sha;
    protected $size;
    protected $url;
    protected $html_url;
    protected $git_url;
    protected $download_url;
    protected $type;

    public function __construct(RepositoryReader $reader, array $readable)
    {
        $this->reader = $reader;

        // If $readable is null, mean directory is index of the repository.
        if (!empty($readable)) {
            $this->mapProperties($readable);
        }
    }

    protected function mapProperties($readable)
    {
        foreach ($readable as $property => $value) {
            $this->map($property, $value);
        }
    }

    protected function map($property, $value)
    {
        if (!property_exists($this, $property)) {
            return;
        }

        $this->{'set' . ucfirst(Str::camel($property))}($value);
    }

    /**
     * @return RepositoryReader
     */
    public function getReader(): RepositoryReader
    {
        return $this->reader;
    }

    /**
     * @param RepositoryReader $reader
     */
    public function setReader(RepositoryReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param null $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * @param mixed $sha
     */
    public function setSha($sha)
    {
        $this->sha = $sha;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHtmlUrl()
    {
        return $this->html_url;
    }

    /**
     * @param mixed $html_url
     */
    public function setHtmlUrl($html_url)
    {
        $this->html_url = $html_url;
    }

    /**
     * @return mixed
     */
    public function getGitUrl()
    {
        return $this->git_url;
    }

    /**
     * @param mixed $git_url
     */
    public function setGitUrl($git_url)
    {
        $this->git_url = $git_url;
    }

    /**
     * @return mixed
     */
    public function getDownloadUrl()
    {
        return $this->download_url;
    }

    /**
     * @param mixed $download_url
     */
    public function setDownloadUrl($download_url)
    {
        $this->download_url = $download_url;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}