[![Build Status](https://img.shields.io/github/actions/workflow/status/zircote/swagger-php/build.yml?branch=master)](https://github.com/xserg/laravel-mailopost/actions?query=workflow:build)
[![Total Downloads](https://img.shields.io/packagist/dt/zircote/swagger-php.svg)](https://packagist.org/packages/zircote/swagger-php)
[![License](https://img.shields.io/badge/license-Apache2.0-blue.svg)](LICENSE)

# laravel-mailopost

Laravel Mail driver for mailopost.ru

## Features

- Send emails with api mailopost.ru
- Support email templates with additional params

## Requirements

`laravel-mailopost` requires at least  PHP 8.1 and Laravel 9.

## Installation (with [Composer](https://getcomposer.org))

```shell
composer require xserg/laravel-mailopost
```

## Usage

Register the service provider:

In config/app.php:

### Service configuration


Add a new section to config/services.php for the API's URL and authorization key,
add mailer, url and key to .env:

```php
'mailopost_mail' => [
    'url' => env('MAILOPOST_MAIL_URL'),
    'key' => env('MAILOPOST_MAIL_API_KEY')
],

MAIL_MAILER=mailopost
MAILOPOST_MAIL_URL="https://api.mailopost.ru"
MAILOPOST_MAIL_API_KEY="your_key"
```

### Register the service provider

```php
'providers' => [
    ...
    // Illuminate\Mail\MailServiceProvider::class,
    Xserg\LaravelMailopost\Providers\MailoPostServiceProvider::class,
],
```
Make sure to copy out Laravel's MailServiceProvider.

### Mailer Config

In config/mail.php, under mailers, you need to add a new entry:
```php
'mailopost' => [
    'transport' => 'mailopost',
],
```

### Send Mail

Create Mailable class, with envelope method
if template_id specified, use mailopost mail template

```php
public function envelope(): Envelope
{
    return new Envelope(

        metadata: [
          'template_id' => your_template_id,
          'email'     => $this->data['email'],
        ],
    );
}
```

- [Laravel Documrntation](https://laravel.com/docs/10.x/mail#sending-mail)
- [Mailopost Documentation](https://mailopost.ru/api.html)
