# Routing

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bengr/routing.svg?style=flat-square)](https://packagist.org/packages/bengr/routing)
[![Total Downloads](https://img.shields.io/packagist/dt/bengr/routing.svg?style=flat-square)](https://packagist.org/packages/bengr/routing)

Package for implementing multiple solutions for routing in laravel application

## Installation

You can install the package via composer:

```bash
composer require bengr/routing
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="routing-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="routing-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="routing-views"
```

## Usage

There is no other configuration needed than just adjust routing.php config for needs of your application

## Credits

- [matejkrenek](https://github.com/matejkrenek)
