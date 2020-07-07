# Laravel commands

[![Packagist](https://img.shields.io/packagist/v/guysolamour/laravel-commands.svg)](https://packagist.org/packages/guysolamour/command)
[![Packagist](https://poser.pugx.org/guysolamour/laravel-commands/d/total.svg)](https://packagist.org/packages/guysolamour/command)
[![Packagist](https://img.shields.io/packagist/l/guysolamour/laravel-commands.svg)](https://packagist.org/packages/guysolamour/command)

## La documentation anglaise est disponible [ici](README.md)

## Préambule

Ce package est une collection de commandes artisan pour laravel

## Installation via composer

```bash
composer require guysolamour/laravel-commands
```

### Publication de la configuration

```bash
php artisan vendor:publish --provider="Guysolamour\Command\ServiceProvider" --tag="config"
```

## Un tour des commandes disponible

### Créer une base de donnée

```bash
php artisan cmd:db:create
```

Par défaut, les informations de connection  a la base de donné seront récupérées dans le fichier **.env** .

Le nom de la base de donné peut être passé en argument.

```bash
php artisan cmd:db:create blog
```

**NB:**

- Les drivers supportés sont (mysql & sqlite)

Le driver peut être changé avec l'option --connection qui est mysql par défaut

```bash
php artisan cmd:db:create blog --connection=sqlite
```

Pour le driver mysql, on peut changer les informations de connexion avec ces options.

```bash
php artisan cmd:db:create blog
--connection=mysql
--username=root
--password=root
--port=3306
```

### Supprimer une base de donnée

```bash
php artisan cmd:db:drop
```

Par défaut, les informations de connection  a la base de donné seront récupérées dans le fichier **.env** .

Le nom de la base de donné peut être passé en argument.

```bash
php artisan cmd:db:drop  blog
```

**NB:**

- Les drivers supportés sont (mysql & sqlite)

Pour le driver mysql, on peut changer les informations de connexion avec ces options.

```bash
php artisan cmd:db:drop blog --connection=sqlite
```

Pour le driver mysql, on peut changer les informations de connexion avec ces options.

```bash
php artisan cmd:db:drop blog
--connection=mysql
--username=root
--password=root
--port=3306
```

### Créer un trait

```bash
php artisan cmd:make:trait Sluggable
```

Les traits sont stockés dans le ***App/Traits***.
Ce dossier peut être changé avec l'option _'folder'_

```bash
php artisan cmd:make:trait Sluggable --folder=Traits
```

### Créer un service provider

```bash
php artisan cmd:make:provider ViewServiceProvider
```

Les providers sont stockés dans le ***App/Providers***.

Ce dossier peut être changé avec l'option _'folder'_

```bash
php artisan cmd:make:provider ViewServiceProvider --folder=Providers
```

### Créer un helper

```bash
php artisan cmd:make:helper helpers
```

Les helpers sont stockés dans le ***App/Helpers***.

Ce dossier peut être changé avec l'option _'folder'_

```bash
php artisan cmd:make:helper helpers --folderHhelpers
```

### Sécurité

Si vous découvrez des problèmes liés à la sécurité, veuillez envoyer un e-mail au lieu d'utiliser le système de issue. Le mail est disponible dans le fichier *composer.json*
