<?php

    require __DIR__ . '/../vendor/autoload.php';
    
    use Embryo\Error\ErrorHandler;
    use Embryo\Error\Middleware\ErrorHandlerMiddleware;
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Factory\{ResponseFactory, ServerRequestFactory};
    use Embryo\Http\Server\MiddlewareDispatcher;
    use Embryo\Log\StreamLogger;
    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    $logPath      = __DIR__.DIRECTORY_SEPARATOR.'logs';
    $request      = (new ServerRequestFactory)->createServerRequestFromServer();
    $response     = (new ResponseFactory)->createResponse(200);
    $logger       = new StreamLogger($logPath);
    $errorHandler = (new ErrorHandler)->setLogger($logger);
    $emitter      = new Emitter;
    
    class TestMiddleware implements MiddlewareInterface
    {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            throw new \Exception('This is a test error!');
        }
    }

    $middleware = new MiddlewareDispatcher([
        (new ErrorHandlerMiddleware)->setErrorHandler($errorHandler),
        new TestMiddleware
    ]);
    $response = $middleware->dispatch($request, $response);

    $emitter->emit($response);