<?php 

    /**
     * ErrorHandler
     * 
     * Handler for errors.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error
     */

    namespace Embryo\Error;

    use Embryo\Error\ErrorHandlerInterface;
    use Embryo\Error\Traits\{ErrorFormatTrait, ErrorLogTrait};
    use Embryo\Http\Factory\ResponseFactory;
    use Embryo\Http\Factory\Stream;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Log\LoggerInterface;

    class ErrorHandler implements ErrorHandlerInterface
    {   
        use ErrorFormatTrait;
        use ErrorLogTrait;

        /**
         * @var array
         */
        private $types = [
            'plain' => ['text/plain', 'text/css', 'text/javascript'],
            'html'  => ['text/html'],
            'json'  => ['application/json'],
            'xml'   => ['text/xml']
        ];

        /**
         * @var bool $displayDetails
         */
        private $displayDetails = true;

        /**
         * @var bool $logErrors
         */
        private $logErrors = true;

        /**
         * @var LoggerInterface $logger
         */
        protected $logger;

        /**
         * Set if display detalis error and write
         * error in log file.
         *
         * @param boolean $displayDetails
         * @param boolean $logErrors
         */
        public function __construct($displayDetails = true, $logErrors = true)
        {
            $this->displayDetails = $displayDetails;
            $this->logErrors      = $logErrors;
        }

        /**
         * Set PSR logger.
         *
         * @param LoggerInterface $logger
         * @return self
         */
        public function setLogger(LoggerInterface $logger): self
        {
            $this->logger = $logger;
            return $this;
        }

        /**
         * Process request and exception
         * and produce a response.
         *
         * @param ServerRequestInterface $request
         * @param \Throwable $exception
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
        {
            $code     = ($exception->getCode() === 0) ? 500 : $exception->getCode();
            $response = (new ResponseFactory)->createResponse($code);
            $accept   = $request->getHeaderLine('Accept');

            if ($this->logErrors) {
                $this->log($request, $response, $exception);
            }

            foreach ($this->types as $method => $types) {
                foreach ($types as $type) {
                    if (stripos($accept, $type) !== false) {
                        
                        $output   = $this->{$method}($exception, $response->getReasonPhrase());
                        $body     = 
                        $response = $response->write($output);
                        return $response->withHeader('Content-Type', $type);

                    }
                }
            }
            return $response;
        }
    }