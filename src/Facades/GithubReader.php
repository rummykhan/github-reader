<?php

namespace GithubReader\Facades;


use Illuminate\Support\Facades\Facade;

class GithubReader extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'github-reader';
    }
}