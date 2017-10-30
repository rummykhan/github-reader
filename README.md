# Github Repository Reader

This package helps reading a complete github repository & retrieve any file you want to.
This package is a wrapper around [GrahamCampbell/Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub), But this package is specifically for reading a Repository in its proper format using [Github Official API](https://developer.github.com/)

## Installation

Install using composer

```php
composer require rummykhan/github-reader php-http/guzzle6-adapter
```

## Add Service Provider
Add `ServiceProvider` to `config/app.php` providers array.

```php
\GithubReader\GithubReaderServiceProvider::class,
```

## Add Facade
To use with Facade add

```php
'GithubReader' => \GithubReader\Facades\GithubReader::class,
```

Publish the configuration (github.php)

## Publish Config

```php
php artisan vendor:publish
```
This will publish `github.php` in `config/` directory.

## Update Config

Since [Github has changed the api Rate Limit](https://developer.github.com/changes/2012-10-14-rate-limit-changes/) you may get exception for hourly
rate limit reached. Then you need to [Register Github App](https://developer.github.com/apps/building-integrations/setting-up-and-registering-github-apps/registering-github-apps/)
and add credentials in `config/github.php`.

```php
'app' => [
    'clientId'     => 'xxx**************xxx',
    'clientSecret' => 'xxx**************xxx',
    'method'       => 'application',
    // 'backoff'      => false,
    // 'cache'        => false,
    // 'version'      => 'v3',
    // 'enterprise'   => false,
],
```

## Reading Repository

Reading a repository is as straight forward as it could be.

```php
$repository = app('github-reader')->read('rummykhan', 'github-reader');
```

## Getting content of a file

```php
$repository = app('github-reader')->read('rummykhan', 'github-reader');

$files = $repository->getFiles();

// This method will retrieve file from github
$file = $files->first()->retrieve();

dd($file->getContent());
```

## Query as Collection

Since files and directories are instances of `Illuminate\Support\Collection`, 
You can query both files or dictionaries just like you query a `Illuminate\Support\Collection`

To query in Files just add `InFiles` to all the collection methods.
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$file = $repository->whereInFiles('name', 'LICENSE')->first();

dd($file);
```

To query in Dictionaries just add `InDictionaries` to all the Collection methods.
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$dictionary = $repository->whereInDictionaries('name', 'src')->first();

dd($dictionary);
```

## Find a file in repository

Since the structure of each item either File/Directory in the repository is like below.
```bash
name: "LICENSE"
path: "LICENSE"
sha: "c8a38eeec1767ff114eaf7caf5cda6d0a7f8f33d"
size: 1110
url: "https://api.github.com/repos/rummykhan/github-reader/contents/LICENSE?ref=master"
html_url: "https://github.com/rummykhan/github-reader/blob/master/LICENSE"
git_url: "https://api.github.com/repos/rummykhan/github-reader/git/blobs/c8a38eeec1767ff114eaf7caf5cda6d0a7f8f33d"
download_url: "https://raw.githubusercontent.com/rummykhan/github-reader/master/LICENSE"
type: "file"
```

We can find any all matching files recursively.
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$found = $repository->find('name', 'src');

dd($found);
```


### Contact
[rehan_manzoor@outlook.com](mailto://rehan_manzoor@outlook.com)