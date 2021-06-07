<?php 

    /**
     * ErrorHandlerInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error  
     */

    namespace Embryo\Error\Interfaces;

    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

    interface ErrorHandlerInterface
    {
        /**
         * @param ServerRequestInterface $request 
         * @param \Throwable $exception
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, \Throwable $exception): ResponseInterface;
    }