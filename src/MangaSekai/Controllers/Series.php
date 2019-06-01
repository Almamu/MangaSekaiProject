<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesQuery;
    
    class Series
    {
        /** @var int Max records to show per page */
        const RECORDS_PER_PAGE = 20;
        
        use \MangaSekai\JSON\PaginationResponse;
        use \MangaSekai\Controllers\Security;
        
        function list (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            if ($request->getMethod () == 'POST')
            {
            
            }
            else
            {
                $page = 1;
                
                if ($request->hasQueryStringParameter ('page') === true)
                {
                    $page = $request->getQueryStringParameter ('page');
                }
                
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (
                        $this->paginatedResponse (
                            SeriesQuery::create ()->paginate ($page, self::RECORDS_PER_PAGE)
                        )
                    )
                    ->printOutput ();
            }
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
                ->setcontentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($serie->toArray ())
                ->printOutput ();
        }
    };