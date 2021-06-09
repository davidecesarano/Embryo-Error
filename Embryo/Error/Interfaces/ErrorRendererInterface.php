<?php 

    /**
     * ErrorRendererInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error  
     */

    namespace Embryo\Error\Interfaces;

    use Throwable;
    use Psr\Http\Message\ResponseInterface;

    interface ErrorRendererInterface
    {
        /**
         * @param ResponseInterface $response
         * @param Throwable $exception
         * @return ResponseInterface
         */
        public function render(ResponseInterface $response, Throwable $exception): ResponseInterface;
    }