<?php 

    /**
     * ContentTypeTrait
     * 
     * Get content type from "accept" header request.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error
     */

    namespace Embryo\Error\Traits;

    use Psr\Http\Message\ServerRequestInterface;

    trait ContentTypeTrait 
    {
        /**
         * @var array $types
         */
        private $types = [
            'application/json',
            'application/xml',
            'text/xml',
            'text/html',
        ];

        /**
         * Get Content-type request.
         *
         * @param ServerRequestInterface $request
         * @return string
         */
        protected function getContentType(ServerRequestInterface $request): string
        {
            $accept = $request->getHeaderLine('Accept');
            $contentTypes = array_intersect(explode(',', $accept), $this->types);
            if (count($contentTypes)) {
                return current($contentTypes);
            }
            return 'text/html';
        }
    }