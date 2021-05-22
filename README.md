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

### Create Database Command

```bash
php artisan cmd:db:create databasename
```

You can use ***--help*** option to have more informations about this command

### Drop Database Command

```bash
php artisan cmd:db:drop databasename
```

You can use ***--help*** option to have more informations about this command


### Trait Command

```bash
php artisan cmd:make:trait traitname
```

Folder can be changed with ***--folder*** option

```bash
php artisan cmd:make:trait traitname --folder={folder}
```

You can use ***--help*** option to have more informations about this command

### Service Provider Command

```bash
php artisan cmd:make:provider providername
```

Folder can be changed with ***--folder*** option

```bash
php artisan cmd:make:provider providername --folder={folder}
```

You can use ***--help*** option to have more informations about this command

### Helper Command

```bash
php artisan cmd:make:helper helpername
```

Folder name can be changed with ***--folder*** option

```bash
php artisan cmd:make:helper helpername --folder={folder}
```
You can use ***--help*** option to have more informations about this command

### Seed Command

```bash
php artisan cmd:db:seed
```

If you want to run a specific class you can use ***--class*** option

```bash
php artisan cmd:db:seed --class=UsersTableSeeder
```
You can use ***--help*** option to have more informations about this command

### Security

If you discover any security related issues, please email
instead of using the issue tracker.
