<?php declare (strict_types=1);
    namespace MangaSekai\HTTP;

    class Response
    {
        /** @var string Content-Type for the JSON responses */
        const JSON = 'application/json';
        /** @var string Content-Type for the plaintext responses */
        const PLAIN = 'text/plain';
        /** @var int Auth error code for the API responses */
        const ERROR_AUTH = 1;

        /**
         * @var array Response headers to be sent
         */
        private $headers = array ();

        /**
         * @var int The response code
         */
        private $status = 200;

        /**
         * @var string The data to be returned when the response is handled
         */
        private $content = "";
    
        /**
         * @var bool Indicates if this response object has already emitted output data
         */
        private $outputPerformed = false;

        public function __construct ($contentType = self::PLAIN)
        {
            $this->setContentType ($contentType);

            // response object takes the control over the exception handler
            set_exception_handler (array ($this, 'handleThrowable'));
        }

        public function setHeader (string $header, string $value): self
        {
            $this->headers [$header] = $value;

            return $this;
        }

        public function removeHeader (string $header): self
        {
            if (array_key_exists ($header, $this->headers) === true)
            {
                unset ($this->headers [$header]);
            }

            return $this;
        }

        public function getHeader (string $header): ?string
        {
            if (array_key_exists ($header, $this->headers) === true)
            {
                return $this->headers [$header];
            }

            return null;
        }

        public function setContentType (string $type): self
        {
            $this->setHeader ('Content-Type', $type);

            return $this;
        }

        public function setStatusCode (int $statusCode): self
        {
            $this->status = $statusCode;

            return $this;
        }

        public function getStatusCode (): int
        {
            return $this->status;
        }

        public function getContentType ()
        {
            return $this->getHeader ('Content-Type');
        }

        public function setOutput ($output): self
        {
            $this->content = $output;
            
            return $this;
        }

        public function handleThrowable (\Throwable $ex)
        {
            $this->setStatusCode (500);
            $this->setContentType (self::JSON);

            if (getenv ('APP_ENVIRONMENT') == 'dev')
            {
                $this->setOutput (array ('code' => $ex->getCode (), 'message' => $ex->getMessage (), 'file' => $ex->getFile (), 'line' => $ex->getLine (), 'stacktrace' => $ex->getTraceAsString ()));
            }
            else
            {
                $this->setOutput (array ('code' => $ex->getCode (), 'message' => $ex->getMessage ()));
            }

            $this->printOutput ();
            die ();
        }

        public function printOutput (): void
        {
            if ($this->outputPerformed === true)
            {
                return;
            }
            
            http_response_code ($this->status);

            foreach ($this->headers as $key => $value)
            {
                header ($key . ": " . $value, true);
            }

            switch ($this->getContentType ())
            {
                case self::JSON:
                    echo json_encode ($this->content, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
                    break;
                default:
                    echo $this->content;
                    break;
            }
        }
    };