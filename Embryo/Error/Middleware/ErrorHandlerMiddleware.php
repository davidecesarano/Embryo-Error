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
    
    use Embryo\Error\ErrorHandler;
    use Embryo\Error\Interfaces\ErrorHandlerInterface;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    class ErrorHandlerMiddleware implements MiddlewareInterface
    {   
        /**
         * @var ErrorHandlerInterface $errorHandler
         */
        private $errorHandler;

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
                return $handler->handle($request);                
            } catch (\Throwable $exception) {
                return $this->handleError($request, $exception);
            }
        }

        /**
         * Process the error.
         * 
         * @param ServerRequestInterface $request
         * @param \Throwable $exception
         * @return ResponseInterface
         */
        private function handleError(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
        {
            return $this->errorHandler->process($request, $exception);
        }
    }