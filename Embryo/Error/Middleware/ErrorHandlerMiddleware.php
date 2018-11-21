<?php 

    /**
     * ErrorHandlerMiddleware
     * 
     * Middleware to catch and format errors encountered 
     * while handling the request.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error 
     */

    namespace Embryo\Error\Middleware;
    
    use Embryo\Error\{ErrorHandler, ErrorHandlerException, ErrorHandlerInterface};
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    class ErrorHandlerMiddleware implements MiddlewareInterface
    {   
        /**
         * Set error handler.
         *
         * @param ErrorHandlerInterface $errorHandler
         * @return self
         */
        public function setErrorHandler(ErrorHandlerInterface $errorHandler): self
        {
            $this->errorHandler = $errorHandler;
            return $this;
        }

        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface 
        {
            try {
                set_error_handler([$this, 'handleErrorFunction']);
                return $handler->handle($request);                
            } catch (\Throwable $exception) {
                return $this->handleError($request, $exception);
            }
        }

        /**
         * Process the error.
         * 
         * @param Throwable $exception
         * @return ResponseInterface
         */
        private function handleError(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
        {
            return $this->errorHandler->process($request, $exception);
        }

        /**
         * Error handling with set_error_handler().
         *
         * @param int $code
         * @param string $message
         * @param string $file
         * @param string $line
         * @throws ErrorHandlerException
         */
        public function handleErrorFunction($code, $message, $file, $line){
            throw new ErrorHandlerException($message, 500, $file, $line);
        }
    }