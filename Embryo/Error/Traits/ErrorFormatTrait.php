<?php 

    /**
     * ErrorFormatTrait
     * 
     * Render error in plain, html, json or xml based
     * on the Accept header.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-error
     */

    namespace Embryo\Error\Traits;

    trait ErrorFormatTrait 
    {
        /**
         * Render html error page.
         *
         * @param \Throwable $error
         * @return string
         */
        protected function html(\Throwable $error): string
        {
            $title = 'Application Error';

            if ($this->displayDetails) {
                
                $html = '<p>The application could not run because of the following error:</p>';
                $html .= $this->renderHtmlError($error);

                while ($e = $error->getPrevious()) {
                    $html .= '<h2>Previous exception</h2>';
                    $html .= $this->renderHtmlError($exception);
                }

            } else {
                $html = '<p>A website error has occurred. Sorry for the temporary inconvenience.</p>';
            }
            
            return sprintf(
                "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
                "<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
                "sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{" .
                "display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>",
                $title,
                $title,
                $html
            );
        }

        /**
         * Render JSON error page.
         *
         * @param \Throwable $error
         * @return string
         */
        protected function json(\Throwable $error): string
        {
            $json = [
                'result' => [
                    'error' => [
                        'message'   => 'Application Error',
                        'exception' => []
                    ]
                ]
            ];

            if ($this->displayDetails) {
                
                $json['result']['error']['exception'][] = [
                    'type'    => get_class($error),
                    'code'    => $error->getCode(),
                    'message' => $error->getMessage(),
                    'file'    => $error->getFile(),
                    'line'    => $error->getLine(),
                    'trace'   => explode("\n", $error->getTraceAsString()),
                ];
            }

            return json_encode($json, JSON_PRETTY_PRINT);
        }
        
        /**
         * Render plain error page.
         *
         * @param \Throwable $error
         * @return string
         */
        protected function plain(\Throwable $error): string
        {
            return sprintf('%s: %s in %s on line %d',
                get_class($error),
                $error->getMessage(),
                $error->getFile(),
                $error->getLine()
            );
        }

        /**
         * Render XML error page.
         *
         * @param \Throwable $error
         * @return string
         */
        protected function xml(\Throwable $error): string
        {
            $xml = "<error>\n  <message>Application Error</message>\n";
                if ($this->displayDetails) {

                    $xml .= "<exception>\n";
                        $xml .= "<type>".get_class($error)."</type>\n";
                        $xml .= "<code>".$error->getCode()."</code>\n";
                        $xml .= "<message>".sprintf('<![CDATA[%s]]>', str_replace(']]>', ']]]]><![CDATA[>', $error->getMessage())) . "</message>\n";
                        $xml .= "<file>".$error->getFile()."</file>\n";
                        $xml .= "<line>".$error->getLine()."</line>\n";
                        $xml .= "<trace>".sprintf('<![CDATA[%s]]>', str_replace(']]>', ']]]]><![CDATA[>', $error->getTraceAsString()))."</trace>\n";
                    $xml .= "</exception>\n";
            
                }
            $xml .= "</error>";
            return $xml;
        }

        /**
         * Render HTML previous error.
         *
         * @param \Throwable $error
         * @return string
         */
        private function renderHtmlError(\Throwable $error): string
        {
            $html = '<h2>Details</h2>';
            $html .= sprintf('<div><strong>Type:</strong> %s</div>', get_class($error));
            $html .= sprintf('<div><strong>Code:</strong> %s</div>', $error->getCode());
            $html .= sprintf('<div><strong>Message:</strong> %s</div>', htmlentities($error->getMessage()));
            $html .= sprintf('<div><strong>File:</strong> %s</div>', $error->getFile());
            $html .= sprintf('<div><strong>Line:</strong> %s</div>', $error->getLine());
            $html .= '<h2>Trace</h2>';
            $html .= sprintf('<pre>%s</pre>', htmlentities($error->getTraceAsString()));
            return $html;
        }
    }