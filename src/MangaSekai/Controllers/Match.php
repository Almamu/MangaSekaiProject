<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    class Match
    {
        use \MangaSekai\Controllers\Security;
        
        function search (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            // we can discard the returned data from this function
            $this->validateUser ($request);
            
            $bodyData = $request->getBodyData ();
            
            if (array_key_exists ('name', $bodyData) == false)
            {
                throw new \Exception ('Search criteria not specified', \MangaSekai\API\ErrorCodes::CANNOT_FIND_MATCH);
            }
            
            $anilist = new \MangaSekai\AniList\Matcher ();
            $matches = $anilist->match ($bodyData ['name']);
    
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($matches)
                ->printOutput ();
        }
    };