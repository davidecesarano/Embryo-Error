<?php 

    /**
     * ErrorHandlerException
     * 
     * Exception for to handler error in set_error_handler().
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error 
     */
    
    namespace Embryo\Error;

    class ErrorHandlerException extends \Exception 
    {
        /**
         * Extends exception and set file and line.
         *
         * @param string $message
         * @param int $code
         * @param string $file
         * @param int $line
         * @param \Exception $previous
         */
        public function __construct(string $message = '', int $code = 0, string $file = null, int $line = null, \Exception $previous = null)
        {
            $this->file = $file;
            $this->line = $line;
            parent::__construct($message, $code, $previous);
        }
    }