# Github Repository Reader

**NOTE:** This package is a wrapper around [GrahamCampbell/Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub), But this package is specifically for reading a Repository in its proper format using [Github Official API](https://developer.github.com/)

## Installation

Install using composer

`composer require rummykhan/github-reader php-http/guzzle6-adapter`

Add `ServiceProvider` to `config/app.php` providers array.

```php
\GithubReader\GithubReaderServiceProvider::class,
```

To use with Facade add

```php
'GithubReader' => \GithubReader\Facades\GithubReader::class,
```

Publish the configuration (github.php)

```php
php artisan vendor:publish
```

## Reading Repository

Reading a repository is as straight forward as it could be.

```php
$repository = app('github-reader')->read('rummykhan', 'github-reader');
```

## Caveats

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

### Contact
[rehan_manzoor@outlook.com](mailto://rehan_manzoor@outlook.com)