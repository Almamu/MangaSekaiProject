<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesQuery;
    
    class Series
    {
        use \MangaSekai\JSON\PaginationResponse;
        use \MangaSekai\Controllers\Security;
        
        function list (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    SeriesQuery::create ()->find ()->toArray ()
                )
                ->printOutput ();
        }
        
        function info (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $serie = SeriesQuery::create ()->findOneById ((int) $request->getParameter ('id'));
            
            if ($serie == null)
            {
                throw new \Exception ('The specified series does not exist', \MangaSekai\API\ErrorCodes::UNKNOWN_SERIES);
            }
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($serie->toArrayWithAuthorsAndGenres ())
                ->printOutput ();
        }
    };