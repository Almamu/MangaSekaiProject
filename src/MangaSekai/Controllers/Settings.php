<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;

    use \MangaSekai\Database\SettingsQuery;
    
    class Settings
    {
        use \MangaSekai\Controllers\Security;
        
        function list (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $bodyData = $request->getBodyData ();
            
            if ($request->getMethod () == 'POST')
            {
                if (array_key_exists ('name', $bodyData) === false || array_key_exists ('value', $bodyData) === false)
                {
                    throw new \Exception ('The setting update is missing information', \MangaSekai\API\ErrorCodes::CALL_MISSING_PARAMETERS);
                }
    
                // find the setting we're trying to modify first
                $setting = SettingsQuery::create ()
                                        ->findOneByName ($bodyData ['name']);
    
                if ($setting == null)
                {
                    $setting = new \MangaSekai\Database\Settings ();
                    $setting->setName ($bodyData ['name']);
                }
    
                $setting->setValue ($bodyData ['value']);
                $setting->save ();
    
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (array ())
                    ->printOutput ();
            }
            elseif ($request->getMethod () == 'GET')
            {
                $search = SettingsQuery::create ();
                
                if ($request->hasQueryStringParameter ('name') === true)
                {
                    $search->filterByName ($request->getQueryStringParameter ('name'));
                }
                
                $result = $search->findOne ();
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput ($result->toArray ())
                    ->printOutput ();
            }
        }
    }