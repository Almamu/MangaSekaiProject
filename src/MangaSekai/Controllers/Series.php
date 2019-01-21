<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesQuery;
    
    class Series
    {
        /** @var int Max records to show per page */
        const RECORDS_PER_PAGE = 20;
        
        use \MangaSekai\JSON\PaginationResponse;
        
        function list (\MangaSekai\HTTP\Request $request)
        {
            $page = 1;
            
            if ($request->hasQueryStringParameter ('page') === true)
            {
                $page = $request->getQueryStringParameter ('page');
            }
            
            $request
                ->makeResponse ()
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    $this->paginatedResponse (
                        SeriesQuery::create ()->paginate ($page, self::RECORDS_PER_PAGE)
                    )
                )
                ->printOutput ();
        }
        
        function info (\MangaSekai\HTTP\Request $request)
        {
            $serie = SeriesQuery::create ()->findOneById ((int) $request->getParameter (':id'));
            
            if ($serie == null)
            {
                throw new \Exception ('The specified series does not exist');
            }
            
            $request
                ->makeResponse ()
                ->setcontentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($serie->toArray ())
                ->printOutput ();
        }
    };