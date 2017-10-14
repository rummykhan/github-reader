<?php

namespace GithubReader\Github;


abstract class Content
{
    protected $name;
    protected $path = null;
    protected $sha;
    protected $size;
    protected $url;
    protected $html_url;
    protected $git_url;
    protected $download_url;
    protected $type;

    public function __construct(array $readable)
    {
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

        $this->{$property} = $value;
    }
}