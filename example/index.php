<?php 
    
    require __DIR__ . '/../vendor/autoload.php';
    
    use Embryo\Error\ErrorHandler;
    use Embryo\Error\Middleware\ErrorHandlerMiddleware;
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Factory\ServerRequestFactory;
    use Embryo\Http\Factory\ResponseFactory;
    use Embryo\Http\Server\MiddlewareDispatcher;
    use Embryo\Log\StreamLogger;

    $logPath      = __DIR__.DIRECTORY_SEPARATOR.'logs';
    $request      = (new ServerRequestFactory)->createServerRequestFromServer();
    $response     = (new ResponseFactory)->createResponse(200);
    $logger       = new StreamLogger($logPath);
    $errorHandler = (new ErrorHandler)->setLogger($logger);
    
    $middleware = new MiddlewareDispatcher;
    $middleware->add(
        (new ErrorHandlerMiddleware)
            ->setErrorHandler($errorHandler)
    );
    $response = $middleware->dispatch($request, $response);