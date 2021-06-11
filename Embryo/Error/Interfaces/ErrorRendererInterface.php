<?php 

    /**
     * ErrorRendererInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error  
     */

    namespace Embryo\Error\Interfaces;

    use Throwable;
    use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};

    interface ErrorRendererInterface
    {
        /**
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @param Throwable $exception
         * @return ResponseInterface
         */
        public function render(ServerRequestInterface $request, ResponseInterface $response, Throwable $exception): ResponseInterface;
    }