#!/usr/bin/env bash

rm -rf vendor/*
composer install -a -o --no-dev
composer require php-http/guzzle6-adapter --no-update -a -o
box build
git checkout -- composer.json
composer update