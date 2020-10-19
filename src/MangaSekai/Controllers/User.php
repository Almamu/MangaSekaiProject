<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\UsersQuery;
    use \MangaSekai\Database\SettingsQuery;
    
    class User
    {
        use \MangaSekai\Controllers\Security;
    
        function user (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $storage = $this->validateUser ($request);
            
            $user = UsersQuery::create ()->findOneById ($storage->get ('id'));
            
            if ($request->getMethod () == 'GET')
            {
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (
                        array (
                            'Username' => $user->getUsername ()
                        )
                    )
                    ->printOutput ();
            }
            else if ($request->getMethod () == 'POST')
            {
                $bodyData = $request->getBodyData ();
                
                if ($user->getPassword () != hash ('sha256', $bodyData ['OldPassword']))
                {
                    throw new \Exception ("Old password doesn't match");
                }
    
                if (array_key_exists ('NewPassword', $bodyData) == true)
                {
                    if (empty ($bodyData ['NewPassword']) == true)
                    {
                        throw new \Exception ("New password cannot be empty");
                    }
                    
                    $user->setPassword (hash ('sha256', $bodyData ['NewPassword']));
                }
                
                $user
                    ->setUsername ($bodyData ['Username'])
                    ->save ();
            }
        }
        
        function userlist (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $storage = $this->validateUser ($request);
    
            // check that the user can change settings first
            $permissionSetting = SettingsQuery::create ()->findOneByName ('administrator_users');
    
            if (in_array ($storage->get ('id'), $permissionSetting->getValue ()) == false)
            {
                throw new \Exception ("Only admins allowed");
            }
            
            $users = UsersQuery::create ()->find ();
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    $users->toKeyValue ('Id', 'Username')
                )->printOutput ();
        }
        
        function login (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            if ($request->hasHeader ('Authorization') === false)
            {
                throw new \Exception ('Authorization header not received', \MangaSekai\API\ErrorCodes::AUTHENTICATION_FAILED);
            }
            
            $authorization = $request->getHeader ('Authorization');
            
            if (strpos ($authorization, 'Basic ') !== 0)
            {
                throw new \Exception ('Expected basic authorization', \MangaSekai\API\ErrorCodes::AUTHENTICATION_FAILED);
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
                throw new \Exception ('Unknown username and/or password', \MangaSekai\API\ErrorCodes::AUTHENTICATION_FAILED);
            }
            
            // user is correct, create new session
            $storage = new \MangaSekai\Storage\SessionStorage (
                \MangaSekai\Storage\SessionStorage::generateToken ()
            );
            
            $storage->set ('expire_time', strtotime ('+1 day'));
            $storage->set ('id', $user->getId ());
            
            $admins = SettingsQuery::create ()->findOneByName ('administrator_users');
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'token' => $storage->getToken (),
                        'expire_time' => $storage->get ('expire_time'),
                        'isadmin' => in_array ($user->getId (), $admins->getValue ())
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