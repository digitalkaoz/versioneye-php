#a PHP CLI/Library for the VersionEye API

see https://www.versioneye.com/api/v2/swagger_doc.json for API documentation

[![Build Status](https://travis-ci.org/digitalkaoz/versioneye-php.svg?branch=master)](https://travis-ci.org/digitalkaoz/versioneye-php)

[![Dependency Status](https://www.versioneye.com/user/projects/5411b8a68d7ae10d4c009233/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5411b8a68d7ae10d4c009233)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/versioneye-php/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/digitalkaoz/versioneye-php/version.svg)](https://packagist.org/packages/digitalkaoz/versioneye-php)
[![Latest Unstable Version](https://poser.pugx.org/digitalkaoz/versioneye-php/v/unstable.svg)](//packagist.org/packages/digitalkaoz/versioneye-php)
[![Total Downloads](https://poser.pugx.org/digitalkaoz/versioneye-php/downloads.svg)](https://packagist.org/packages/digitalkaoz/versioneye-php)

##Installation

```
$ composer require "digitalkaoz/versioneye-php" *
```

##Usage

all API endpoints are implemented, see https://www.versioneye.com/api/ for their docs.


###programmatic:

```php
<?php

use Rs\VersionEye\Client;

$api = (new Client())->api('services'); // Rs\VersionEye\Api\Services
$api->ping(); //array

//other implemented APIs
$api = (new Client())->api('github'); // Rs\VersionEye\Api\Github
$api = (new Client())->api('me'); // Rs\VersionEye\Api\Me
$api = (new Client())->api('products'); // Rs\VersionEye\Api\Products
$api = (new Client())->api('sessions'); // Rs\VersionEye\Api\Sessions
$api = (new Client())->api('users'); // Rs\VersionEye\Api\Users

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

now you dont have to pass your token on each call
##TODO

* need a clever way for outputting results in a proper format for each api

##CLI Tool

```
$ vendor/bin/box build
$ php versioneye-php.phar
```

##Tests

```
$ vendor/bin/phpspec run
```