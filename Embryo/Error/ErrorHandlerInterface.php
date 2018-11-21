<?php 

    /**
     * ErrorHandlerInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error  
     */

    namespace Embryo\Error;

    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;

    interface ErrorHandlerInterface
    {
        public function process(ServerRequestInterface $request, \Throwable $exception): ResponseInterface;
    }