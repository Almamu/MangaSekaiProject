<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\UsersQuery;
    
    class User
    {
        use \MangaSekai\Controllers\Security;
        
        function login (\MangaSekai\HTTP\Request $request)
        {
            if ($request->hasHeader ('Authorization') === false)
            {
                throw new \Exception ('Authorization header not received');
            }
            
            $authorization = $request->getHeader ('Authorization');
            
            if (strpos ($authorization, 'Basic ') !== 0)
            {
                throw new \Exception ('Expected basic authorization');
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
                throw new \Exception ('Unknown username and/or password');
            }
            
            // user is correct, create new session
            $storage =  new \MangaSekai\Storage\SessionStorage (
                \MangaSekai\Storage\SessionStorage::generateToken ()
            );
            
            $storage->set ('auth_time', time ());
            $storage->set ('id', $user->getId ());
            
            $request
                ->makeResponse ()
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'token' => $storage->getToken (),
                        'time' => $storage->get ('auth_time')
                    )
                );
        }
        
        function refresh (\MangaSekai\HTTP\Request $request)
        {
            $storage = $this->validateUser ($request);
            $storage->set ('auth_time', time ());
            
            $request
                ->makeResponse ()
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'token' => $storage->getToken (),
                        'time' => $storage->get ('auth_time')
                    )
                );
        }
    };