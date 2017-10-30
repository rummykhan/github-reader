# Github Repository Reader

This package helps reading a complete github repository & retrieve any file you want to.
This package is a wrapper around [GrahamCampbell/Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub), But this package is specifically for reading a Repository in its proper format using [Github Official API](https://developer.github.com/)

## Github Official Format

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
Only the type is changed for `directory` / `symlink`.

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

dd($repository);
```

## Getting content of a file

```php
$repository = app('github-reader')->read('rummykhan', 'github-reader');

$files = $repository->getFiles();

// This method will retrieve file from github
$file = $files->first()->retrieve();

dd($file->getContent());
```

## Query Files

Since files and directories are instances of `Illuminate\Support\Collection`, 
You can query both files or dictionaries just like you query a `Illuminate\Support\Collection`

There are two ways you can query files.
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$file = $repository->getFiles()->where('name', 'LICENSE')->first();

dd($file);
```
**OR**

To query in Files just add `InFiles` to all the collection methods.
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$file = $repository->whereInFiles('name', 'LICENSE')->first();

dd($file);

```

## Query Directories
```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$dictionary = $repository->getDirectories()->where('name', 'src')->first();

dd($dictionary);
```

**OR**

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

We can find any all matching directory/files recursively.

```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$found = $repository->find('type', 'file');

dd($found);
```

Third parameter in the find is to find recursively

```php
$repository = app('github-reader')
        ->read('rummykhan', 'github-reader');

$found = $repository->find('name', 'File.php', true);

dd($found);
```

This `find` method will return a collection.


## Available Methods

### 2. GithubReader\RepositoryReader 

```php

$reader = app('github-reader')
```

| Name                                          | Purpose |
| ----------------------------------------------------------- |:-------------:|
| `init($organization, $repositoryName, $connection = null)` | Initialize the repository with organization/user and repository name. |
| `getConnection()`      									| Getter for connection. |
| `setConnection(string $connection)` 						| Set the connection.|
| `getOrganization()` 										| Getter for Organization. |
| `setOrganization(string $organization)` 					| Setter for organization.|
| `getRepositoryName()` 									| Getter for repository name. |
| `setRepositoryName($repositoryName)` 						| Setter for repository name. |
| `read($organization = null, $repositoryName = null, $connection = null)` | read a repository completely. |
| `readPath($path = null)` 									| Read only certain path of the repository. |

To only use read path.

```php
$directory = app('github-reader')
	->setOrganization('rummykhan')
	->setRepositoryName('github-reader')
	->readPath('src');
```

### 2. GithubReader\Github\Directory Or Repository 

```php
$repository = app('github-reader')->read('rummykhan','github-reader');
 ```

| Name                                          | Purpose |
| --------------------------------------------- |:-------------:|
| `getFiles()`     								| Get all files in that directory. |
| `getDirectories()`      						| Get all directories in that directory. |
| `listAll()` 									| Get all files and directories in that directory.|
| `retrieve()` 									| Alias of `listAll()`. |
| `find($key, $name, $all = true)` 				| Find in all files and directories if `$all=true` it will find recursively.|
| `findDirectory($key, $name, $all = false)` 	| Find in directoris and if `$all=true` it will find recursively. |
| `findFile($key, $name, $all = false)` 		| Find in files and if `$all=true` it will find recursively.      |
| `toArray()` 									| Convert the object to array representation. |
| `toJson($options = 0)` 						| Convert the object to JSON representation. |

### 3. GithubReader\Github\File

```php
$repository = app('github-reader')->read('rummykhan','github-reader');

$file = $repository->getFiles()->first();
```

| Name                                          | Purpose |
| --------------------------------------------- |:-------------:|
| `retrieve()` 									| It will give you instance of `Github\Github\FileContent`. |

### 4. GithubReader\Github\FileContent

```php
$repository = app('github-reader')->read('rummykhan','github-reader');
$file = $repository->getFiles()->first();
$fileContent = $file->retrieve();
```
| Name                                          | Purpose |
| --------------------------------------------- |:-------------:|
| `getContent()` 							    | It will give content of file in plain text. |

### Contact
[rehan_manzoor@outlook.com](mailto://rehan_manzoor@outlook.com)