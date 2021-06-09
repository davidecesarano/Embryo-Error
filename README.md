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
## Usage
You may quickly test this using the built-in PHP server going to http://localhost:8000.
```php
use Embryo\Error\ErrorHandler;
use Embryo\Error\Middleware\ErrorHandlerMiddleware;
use Embryo\Http\Factory\{ResponseFactory, ServerRequestFactory};
use Embryo\Http\Server\RequestHandler;
use Embryo\Log\StreamLogger;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

// Set path log files and stream logger
$logPath = '/path/to/logs';
$logger = new StreamLogger($logPath);

// Set error handler
$errorHandler = (new ErrorHandler)->setLogger($logger);

// Set PSR Request and Response
$request = (new ServerRequestFactory)->createServerRequestFromServer();
$response = (new ResponseFactory)->createResponse(200);

// Set custom middleware with exception
class TestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        throw new \Exception('This is a test error!');
    }
}

// ErrorHandler should always be the first middleware in the stack!
$middleware = new RequestHandler([
    (new ErrorHandlerMiddleware)->setErrorHandler($errorHandler),
    new TestMiddleware
]);


$response = $middleware->dispatch($request, $response);
```

## Options
### `__construct(bool $displayDetails = true, bool $logErrors = true)`
The error handler includes detailed information on error diagnostics and saving to a log file. To enable this you need to set the displayDetails and logErrors setting to true.

### `setLogger(LoggerInterface $logger)`
You must set a PSR logger if logErrors is set to true.

### `setRenderer(ErrorRendererInterface $renderer = null)`
You can set up a custom renderer for printing errors.

```php
use Embryo\Error\Interfaces\ErrorRendererInterface;
use Psr\Http\Message\ResponseInterface;

class CustomRenderer implements ErrorRendererInterface 
{
    public function render(ResponseInterface $response, \Throwable $exception): ResponseInterface
    {
        // custom logic...
        return $response;
    }
}

$errorHandler = (new ErrorHandler)->setRenderer(CustomRenderer::class);
```