<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    trait Security
    {
        protected function validateUser (\MangaSekai\HTTP\Request $request): \MangaSekai\Storage\SessionStorage
        {
            if ($request->hasHeader ('Authorization') === false)
            {
                throw new \Exception ('Authorization header not specified', \MangaSekai\API\ErrorCodes::AUTHENTICATION_REQUIRED);
            }
            
            $authorization = $request->getHeader ('Authorization');
            
            if (strpos ($authorization, 'Bearer ') !== 0)
            {
                throw new \Exception ('Expected bearer authorization', \MangaSekai\API\ErrorCodes::AUTHENTICATION_REQUIRED);
            }
            
            $token = substr ($authorization, strlen ('Bearer '));
            $storage = new \MangaSekai\Storage\SessionStorage ($token);
            
            if ($storage->get ('expire_time') < time ())
            {
                throw new \Exception ('This session has expired', \MangaSekai\API\ErrorCodes::AUTHENTICATION_REQUIRED);
            }
            
            return $storage;
        }
    };