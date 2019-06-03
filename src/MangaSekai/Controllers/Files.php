<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    class Files
    {
        use \MangaSekai\Controllers\Security;
        
        function search (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            // we can discard the returned data from this function
            $this->validateUser ($request);
            
            $bodyData = $request->getBodyData ();
            
            if (array_key_exists ('directory', $bodyData) == false || $bodyData ['directory'] == '')
            {
                $bodyData ['directory'] = '/';
            }
            
            $bodyData ['directory'] = realpath ($bodyData ['directory']);
            $result = array ();
            
            if (is_dir ($bodyData ['directory']) == true)
            {
                $directory = opendir ($bodyData ['directory']);
                
                if ($directory)
                {
                    while (($entry = readdir ($directory)) !== false)
                    {
                        if ($entry == '.' || $entry == '..') continue;
        
                        if (is_dir ($bodyData ['directory'] . '/' . $entry) == true)
                        {
                            $result [] = array ('type' => 'dir', 'name' => $entry);
                        }
                        else
                        {
                            $result [] = array ('type' => 'file', 'name' => $entry);
                        }
                    }
    
                    closedir ($directory);
                }
            }
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'directory' => $bodyData ['directory'],
                        'contents' => $result
                    )
                )
                ->printOutput ();
        }
    };