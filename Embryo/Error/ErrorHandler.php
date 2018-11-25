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
    use Embryo\Error\Traits\{ContentTypeTrait, ErrorFormatTrait, ErrorLogTrait};
    use Embryo\Http\Factory\{ResponseFactory, StreamFactory};
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Log\LoggerInterface;

    class ErrorHandler implements ErrorHandlerInterface
    {   
        use ContentTypeTrait;
        use ErrorFormatTrait;
        use ErrorLogTrait;

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
            $code        = ($exception->getCode() === 0) ? 500 : $exception->getCode();
            $contentType = $this->getContentType($request);

            if ($this->logErrors) {
                $this->log($request, $code, $exception);
            }

            switch ($contentType) {
                case 'application/json':
                    $output = $this->json($exception);
                    break;
                case 'text/xml':
                case 'application/xml':
                    $output = $this->xml($exception);
                    break;
                case 'text/html':
                    $output = $this->html($exception);
                    break;
                default:
                    throw new \UnexpectedValueException("Unknown content type $contentType");
            }

            $body = (new StreamFactory)->createStream($output);
            $response = (new ResponseFactory)->createResponse($code);
            return $response->withHeader('Content-type', $contentType)->withBody($body);
        }
    }