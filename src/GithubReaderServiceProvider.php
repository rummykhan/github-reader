<?php

namespace GithubReader;

use Illuminate\Support\ServiceProvider;

class GithubReaderServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('github-reader', function () {
            return new RepositoryReader($this->app->make('github'));
        });
    }

    public function provides()
    {
        return [
            'github-reader'
        ];
    }
}