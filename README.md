[![Build Status](https://img.shields.io/github/actions/workflow/status/zircote/swagger-php/build.yml?branch=master)](https://github.com/zircote/swagger-php/actions?query=workflow:build)
[![Total Downloads](https://img.shields.io/packagist/dt/zircote/swagger-php.svg)](https://packagist.org/packages/zircote/swagger-php)
[![License](https://img.shields.io/badge/license-Apache2.0-blue.svg)](LICENSE)

# laravel-mailopost

Mail driver for mailopost.ru

## Features

- Send emails with api mailopost.ru
- Support email templates with additional params

## Requirements

`laravel-mailopost` requires at least  PHP 8.1 and Laravel 9.

## Installation (with [Composer](https://getcomposer.org))

```shell
composer require xserg/laravel-mailopost
```
```shell
composer global require xserg/laravel-mailopost
```

## Usage



### Installation

Register the service provider:

In config/app.php:

```php
'providers' => [
    ...
    // Illuminate\Mail\MailServiceProvider::class,
    Xserg\LaravelMailopost\Providers\MailoPostServiceProvider::class,
],
Make sure to copy out Laravel's MailServiceProvider.

#Mailer Config
In config/mail.php, under mailers, you need to add a new entry:

'custom' => [
    'transport' => 'custom',
],
```

- [Mailopost Documentation](https://mailopost.ru/api.html)
