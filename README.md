# Laravel Useful Commands

[![Build Status](https://travis-ci.org/guysolamour/laravel-useful-commands.svg?branch=master)](https://travis-ci.org/guysolamour/laravel-useful-commands)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/guysolamour/laravel-useful-commands/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/guysolamour/laravel-useful-commands/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/guysolamour/laravel-useful-commands/badge.svg?branch=master)](https://coveralls.io/github/guysolamour/laravel-useful-commands?branch=master)

[![Packagist](https://img.shields.io/packagist/v/guysolamour/laravel-useful-commands.svg)](https://packagist.org/packages/guysolamour/laravel-useful-commands)
[![Packagist](https://poser.pugx.org/guysolamour/laravel-useful-commands/d/total.svg)](https://packagist.org/packages/guysolamour/laravel-useful-commands)
[![Packagist](https://img.shields.io/packagist/l/guysolamour/laravel-useful-commands.svg)](https://packagist.org/packages/guysolamour/laravel-useful-commands)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require guysolamour/laravel-useful-commands
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Guysolamour\LaravelUsefulCommands\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Guysolamour\LaravelUsefulCommands\Facades\LaravelUsefulCommands::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Guysolamour\LaravelUsefulCommands\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email 
instead of using the issue tracker.

## Credits

- [](https://github.com/guysolamour/laravel-useful-commands)
- [All contributors](https://github.com/guysolamour/laravel-useful-commands/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
