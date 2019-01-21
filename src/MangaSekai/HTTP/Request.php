<?php declare (strict_types=1);
    namespace MangaSekai\HTTP;
    
    class Request
    {
        /** @var string The request method */
        private $method = "";
        /** @var array The list of parameters the call has */
        private $parameters = array ();
        /** @var array The list of headers the call has */
        private $headers = array ();
        /** @var array The list of parameters sent trough the query string */
        private $queryStringParameters = array ();
        /** @var array|string The contents of the request  */
        private $contentData = null;
        
        private function __construct ($method, $parameters, $headers, $queryStringParameters, $contentData)
        {
            $this->method = $method;
            $this->parameters = $parameters;
            $this->headers = $headers;
            $this->queryStringParameters = $queryStringParameters;
            $this->contentData = $contentData;
        }
        
        public function getMethod (): string
        {
            return $this->method;
        }
        
        public static function makeRequestFromServerGlobal ($parameters)
        {
            $headers = array ();
            $bodyContent = null;
            
            foreach ($_SERVER as $key => $value)
            {
                if (strncmp ('HTTP_', $key, strlen ('HTTP_')) === 0)
                {
                    $tmpKey = substr ($key, strlen ('HTTP_'));
                    $tmpKey = str_replace ('_', '-', $tmpKey);
                    $tmpKey = ucwords (strtolower ($tmpKey), '-');
                    
                    $headers [$tmpKey] = $value;
                }
            }
            
            if (array_key_exists ('Content-Length', $headers) === true)
            {
                if (array_key_exists ('Content-Type', $headers) === true)
                {
                    $bodyContent = json_decode (file_get_contents ('php://input'), true, 512, JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY);
                }
                else
                {
                    $bodyContent = file_get_contents ('php://input');
                }
            }
    
            return new static (
                $_SERVER ['REQUEST_METHOD'],
                $parameters,
                $headers,
                $_GET,
                $bodyContent
            );
        }
        
        function getParameter (string $name)
        {
            return $this->parameters [$name] ?? null;
        }

        function getQueryStringParameter (string $name)
        {
            return $this->queryStringParameters [$name] ?? null;
        }

        function hasQueryStringParameter (string $name)
        {
            return array_key_exists ($name, $this->queryStringParameters);
        }
        
        function getHeader (string $name)
        {
            return $this->headers [$name] ?? null;
        }
        
        function hasHeader (string $name)
        {
            return array_key_exists ($name, $this->headers);
        }

        function makeResponse (): Response
        {
            return new Response ();
        }
        
        function getBodyData ()
        {
            return $this->contentData;
        }
    };