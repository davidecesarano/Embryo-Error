# Embryo Error
PSR-15 middleware to handle errors. This handler inspired by [Slim](https://github.com/slimphp/Slim/tree/3.x/Slim/Handlers).
It receives all uncaught PHP exceptions and converts them to a new HTTP response with the status code.

## Requirements
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))
* A [PSR-15](https://www.php-fig.org/psr/psr-15/) http server request handlers implementation (ex. [Embryo-Middleware](https://github.com/davidecesarano/Embryo-Middleware))
* A PSR response emitter (ex. [Embryo-Emitter](https://github.com/davidecesarano/Embryo-Emitter))

## Install
Using Composer:
```
$ composer require davidecesarano/embryo-error
```
## Example
You may quickly test this using the built-in PHP server going to http://localhost:8000.
```
$ cd example
$ php -S localhost:8000
```