<?php

    /**
     * ErrorLogTrait
     * 
     * Write error log.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error
     */
    
    namespace Embryo\Error\Traits;

    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;

    trait ErrorLogTrait
    {
        /**
         * Write log.
         * 
         * If client error it saves a error log,
         * otherwise it saves a critical error.
         *
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @param Throwable $exception
         * @return void
         */
        protected function log(ServerRequestInterface $request, ResponseInterface $response, \Throwable $exception)
        {
            $message = sprintf('[{code}] [{method}] [{path}] %s: %s in %s on line %d',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
            
            $code   = $response->getStatusCode();
            $method = $request->getMethod();
            $path   = $request->getUri()->getPath();
            
            if ($code >= 400 && $code <= 451) {

                $this->logger->error($message, [
                    'code' => $code,
                    'method' => $method,
                    'path' => $path
                ]);

            } else {

                $this->logger->critical($message, [
                    'code' => $code,
                    'method' => $method,
                    'path' => $path
                ]);

            }
        }
    }
    