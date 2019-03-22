<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\UsersQuery;
    
    class User
    {
        use \MangaSekai\Controllers\Security;
        
        function login (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            if ($request->hasHeader ('Authorization') === false)
            {
                throw new \Exception ('Authorization header not received', \MangaSekai\API\ErrorCodes::AUTHENTICATION);
            }
            
            $authorization = $request->getHeader ('Authorization');
            
            if (strpos ($authorization, 'Basic ') !== 0)
            {
                throw new \Exception ('Expected basic authorization', \MangaSekai\API\ErrorCodes::AUTHENTICATION);
            }
            
            $basic = substr ($authorization, strlen ('Basic '));
            $basic = base64_decode ($basic);
            $username = urldecode (substr ($basic, 0, strpos ($basic, ':')));
            $password = urldecode (substr ($basic, strpos ($basic, ':') + 1));
            
            $user = UsersQuery::create ()
                ->filterByUsername ($username)
                ->filterByPassword (hash ('sha256', $password))
                ->findOne ();
            
            if ($user === null)
            {
                throw new \Exception ('Unknown username and/or password', \MangaSekai\API\ErrorCodes::AUTHENTICATION);
            }
            
            // user is correct, create new session
            $storage =  new \MangaSekai\Storage\SessionStorage (
                \MangaSekai\Storage\SessionStorage::generateToken ()
            );
            
            $storage->set ('expire_time', strtotime ('+1 day'));
            $storage->set ('id', $user->getId ());
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'token' => $storage->getToken (),
                        'expire_time' => $storage->get ('expire_time')
                    )
                )
                ->printOutput();
        }
        
        function refresh (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $storage = $this->validateUser ($request);
            $storage->set ('expire_time', time ());
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'token' => $storage->getToken (),
                        'expire_time' => $storage->get ('expire_time')
                    )
                )
                ->printOutput();
        }
    };