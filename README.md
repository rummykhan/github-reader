# Github Repository Reader

**NOTE:** This package is a wrapper around [GrahamCampbell/Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub), But this package is specifically for reading a Repository in its proper format using [Github Official API](https://developer.github.com/)

## Installation

Install using composer

`composer require rummykhan/github-reader`

Add `ServiceProvider` to `config/app.php` providers array.

```php
\GithubReader\GithubReaderServiceProvider::class,
```

To use with Facade add

```php
'GithubReader' => \GithubReader\Facades\GithubReader::class,
```


## Reading Repository

Reading a repository is as straight forward as it could be.

```php
$repository = app('github-reader')->read('rummykhan', 'github-reader');
```

### Contact
[rehan_manzoor@outlook.com](mailto://rehan_manzoor@outlook.com)