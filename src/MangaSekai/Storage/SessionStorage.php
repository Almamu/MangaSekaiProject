<?php declare(strict_types=1);
    namespace MangaSekai\Storage;
    
    use \Ramsey\Uuid\Uuid;
    
    class SessionStorage
    {
        /** @var string The token this session storage belongs to */
        private $token = '';
        
        function __construct (string $token)
        {
            if (session_name () != $token)
            {
                session_name ($token);
            }
            
            session_start ();
        }

        /**
         * Searches for the given key in the session storage and returns it's value
         *
         * @param string $key The key to get
         *
         * @return mixed The value stored in that key
         */
        public function get (string $key)
        {
            return $_SESSION [$key];
        }

        public function set (string $key, $value)
        {
            $_SESSION [$key] = $value;

            return $this;
        }
        
        public function getToken ()
        {
            return $this->token;
        }
        
        /**
         * Deletes this session storage and all it's data
         */
        public function delete ()
        {
            session_destroy ();
        }

        /**
         * Generates a new auth key that can be used in the system (uuid)
         *
         * @return string The new auth key to send to the user
         * @throws \Exception
         */
        public static function generateToken (): string
        {
            return base64_encode (Uuid::uuid4 ()->getBytes ());
        }
    };