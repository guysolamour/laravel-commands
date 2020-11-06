# Laravel commands

[![Packagist](https://img.shields.io/packagist/v/guysolamour/laravel-commands.svg)](https://packagist.org/packages/guysolamour/command)
[![Packagist](https://poser.pugx.org/guysolamour/laravel-commands/d/total.svg)](https://packagist.org/packages/guysolamour/command)
[![Packagist](https://img.shields.io/packagist/l/guysolamour/laravel-commands.svg)](https://packagist.org/packages/guysolamour/command)

This package is a collection of artisan commands for speed up developmemt with laravel framework.

## Installation

Install via composer

```bash
composer require guysolamour/laravel-commands
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Guysolamour\Command\ServiceProvider" --tag="config"
```

## Usage

### Create Database Command

```bash
php artisan cmd:db:create
```

By default, the package will look for information at the **.env** file in the database section

However, you can pass the name of the database

```bash
php artisan cmd:db:create  {name}
```

Supported drivers are (mysql & sqlite).
The connection can be changed with 'connection' option which is mysql by default.

```bash
php artisan cmd:db:create {name} --connection={mysql|sqlite}
```

For mysql driver, login credentials can be changed with the options below:

```bash
php artisan cmd:db:create {name}
--connection={mysql|sqlite}
--username=root
--password=root
--port=3306
```

### Drop Database Command

```bash
php artisan cmd:db:drop
```

By default, the package will look for information at the **.env** file in the database section

However, you can pass the name of the database.

```bash
php artisan cmd:db:drop  {name}
```

Supported drivers are (mysql & sqlite).
The connection can be changed with 'connection' option which is mysql by default.

```bash
php artisan cmd:db:drop {name} --connection={mysql|sqlite}
```

For mysql driver, login credentials can be changed with the options below:

```bash
php artisan cmd:db:drop {name}
--connection={mysql|sqlite}
--username=root
--password=root
--port=3306
```

### Trait Command

```bash
php artisan cmd:make:trait {name}
```

Folder name can be changed with _'folder'_ option

```bash
php artisan cmd:make:trait {name} --folder={folder}
```

### Service Provider Command

```bash
php artisan cmd:make:provider {name}
```

Folder name can be changed with _'folder'_ option

```bash
php artisan cmd:make:provider {name} --folder={folder}
```

### Helper Command

```bash
php artisan cmd:make:helper {name}
```

Folder name can be changed with _'folder'_ option

```bash
php artisan cmd:make:helper {name} --folder={folder}
```

### Security

If you discover any security related issues, please email
instead of using the issue tracker.
