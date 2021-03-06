# Laravel Migration Builder

## Description

Programmatically define a table and column definitions and write a migration file. Useful for instance where you would like to create migration from an external source such as an api to store the data locally. See [here](https://github.com/blackbricksoftware/laravel-migration-builder-salesforce) for an example usage.

## Installation

### Install package
`composer require blackbricksoftware/laravel-migration-builder --dev`

### Publish configuration
`php artisan vendor:publish --tag=laravel-migration-builder-config`

## Usage

See [src/Commands/ExampleCommand.php](src/Commands/ExampleCommand.php) for an example.

Run `php artisan make:migration-builder:example` to create an migration with this example.

# Acknowledgements

- Andrew Hanks for this [article](https://medium.com/@andrewhanks2402/step-by-step-guide-to-laravel-package-development-82e2865fb278) on how to make a reddit package
- Iftekhar Rifat for this [repo](https://github.com/Agontuk/schema-builder) with a good starting spot for programatically creating migrations.
- Vitaliy Dotsenko for this [write up](https://medium.com/legacybeta/using-composer-2-0-with-psr4-388b78b98aaa) that fixed my autoloading.