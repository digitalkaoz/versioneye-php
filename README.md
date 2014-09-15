#a PHP CLI/Library for the VersionEye API

see https://www.versioneye.com/api/ for API documentation

[![Build Status](https://travis-ci.org/digitalkaoz/versioneye-php.svg?branch=master)](https://travis-ci.org/digitalkaoz/versioneye-php)

[![Dependency Status](https://www.versioneye.com/php/digitalkaoz:versioneye-php/badge.svg?style=flat)](https://www.versioneye.com/php/digitalkaoz:versioneye-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f7633a7e-4577-4a86-b6d9-ccaa75cb7fa0/mini.png)](https://insight.sensiolabs.com/projects/f7633a7e-4577-4a86-b6d9-ccaa75cb7fa0)

[![Latest Stable Version](https://poser.pugx.org/digitalkaoz/versioneye-php/version.svg)](https://packagist.org/packages/digitalkaoz/versioneye-php)
[![Latest Unstable Version](https://poser.pugx.org/digitalkaoz/versioneye-php/v/unstable.svg)](//packagist.org/packages/digitalkaoz/versioneye-php)
[![Total Downloads](https://poser.pugx.org/digitalkaoz/versioneye-php/downloads.svg)](https://packagist.org/packages/digitalkaoz/versioneye-php)

##Installation

first you have to decide which `http adapter` to use. The library supports `guzzlehttp/guzzle` and `kriswallsmith/buzz`. Where guzzle is default.


```
$ composer require "guzzlehttp/guzzle" ~4 //or
$ composer require "kriswallsmith/buzz" ~0.10

$ composer require "digitalkaoz/versioneye-php" *
```

##Usage

all API endpoints are implemented, see https://www.versioneye.com/api/v2/swagger_doc.json for their detailed docs.


### programmatic:

```php
<?php

use Rs\VersionEye\Client;

$api = (new Client())->api('services');     // Rs\VersionEye\Api\Services
$api->ping(); //array

//other implemented APIs
$api = (new Client())->api('github');       // Rs\VersionEye\Api\Github
$api = (new Client())->api('me');           // Rs\VersionEye\Api\Me
$api = (new Client())->api('projects');     // Rs\VersionEye\Api\Projects
$api = (new Client())->api('products');     // Rs\VersionEye\Api\Products
$api = (new Client())->api('sessions');     // Rs\VersionEye\Api\Sessions
$api = (new Client())->api('users');        // Rs\VersionEye\Api\Users

```

### cli:

```
$ bin/versioneye services:ping
$ bin/versioneye products:search symfony
```

##Configuration

to store your generated API Token globally you can create a global config file in your home directory:

`~/.veye.rc` we share the same config file with the ruby cli https://github.com/versioneye/veye

the file would look like:

```rc
:api_key: YOUR_API_TOKEN
```

now you dont have to pass your token on each call!


##CLI Tool

to build a standalone phar, simply execute the following commands.

```
$ vendor/bin/box build
$ php versioneye-php.phar
```

## Commands:

The Commands are autogenerated by introspecting the API Implementations. Each Public Method is a Command, each Method Parameter will be translated into a InputArgument or InputOption.


    github
      github:delete            remove imported project
      github:hook              GitHub Hook
      github:import            imports project file from github
      github:repos             lists your's github repos
      github:show              shows the detailed information for the repository
      github:sync              re-load github data
    me
      me:comments              shows comments of authorized user
      me:favorites             shows favorite packages for authorized user
      me:notifications         shows unread notifications of authorized user
      me:profile               shows profile of authorized user
    products
      products:follow          follow your favorite software package
      products:follow-status   check your following status
      products:references      references
      products:search          search packages
      products:show            detailed information for specific package
      products:unfollow        unfollow given software package
    projects
      projects:all             shows user`s projects
      projects:create          upload project file
      projects:delete          delete given project
      projects:licenses        get grouped view of licences for dependencies
      projects:show            shows the project's information
      projects:update          update project with new file
    services
      services:ping            Answers to request with basic pong.
    sessions
      sessions:close           delete current session aka log out.
      sessions:open            creates new sessions
      sessions:show            returns session info for authorized users
    users
      users:comments           shows user's comments
      users:favorites          shows user's favorite packages
      users:show               shows profile of given user_id


##TODO

* complete output presentations of each api endpoint
* paging results

##Tests

```
$ composer require --dev henrikbjorn/phpspec-code-coverage "1.0.*@dev"
$ vendor/bin/phpspec run
```
