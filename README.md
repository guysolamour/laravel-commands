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


## Usage

### Create database

```bash
php artisan cmd:db:create
```

Fill **.env** database credentials before using this command.

**NB:**

- Supported drivers are (mysql & sqlite)


### Dump database

```bash
php artisan cmd:db:dump {filename=dump.sql}
```

Fill **.env** database credentials before using this command.

**NB:**

- Supported drivers are (mysql & sqlite)


### Drop a database

```bash
php artisan cmd:db:drop
```

Fill **.env** database credentials before using this command.

**NB:**

- Supported drivers are (mysql & sqlite)

### Seed database

```bash
php artisan cmd:db:seed
```

If you want to run all seed ***--all*** option

```bash
php artisan cmd:db:seed --all
```

If you want to run a specific class you can use ***--class*** option

```bash
php artisan cmd:db:seed --class=UsersTableSeeder
```

Run seed in production ***--force*** option

```bash
php artisan cmd:db:seed --force
```



### Create helper file

```bash
php artisan cmd:make:helper helpername
```

Folder name can be changed with ***--folder*** option

```bash
php artisan cmd:make:helper helpername --folder={folder}
```


### Security

If you discover any security related issues, please email
instead of using the issue tracker.
