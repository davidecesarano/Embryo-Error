<?php 

    /**
     * ErrorRendererInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error  
     */

    namespace Embryo\Error\Interfaces;

    use Psr\Http\Message\ResponseInterface;

    interface ErrorRendererInterface
    {
        /**
         * @param ResponseInterface $response
         * @return ResponseInterface
         */
        public function render(ResponseInterface $response): ResponseInterface;
    }