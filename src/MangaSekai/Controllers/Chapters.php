<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\ChaptersQuery;
    
    class Chapters
    {
        /** @var int Max records to show per page */
        const RECORDS_PER_PAGE = 20;
    
        use \MangaSekai\JSON\PaginationResponse;
        use \MangaSekai\Controllers\Security;
    
        function list (\MangaSekai\HTTP\Request $request)
        {
            $this->validateUser ($request);
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
                        ChaptersQuery::create ()
                                     ->filterByIdSeries ($request->getParameter ('id'))
                                     ->paginate ($page, self::RECORDS_PER_PAGE)
                    )
                )
                ->printOutput ();
        }
        
        function pages (\MangaSekai\HTTP\Request $request)
        {
            $this->validateUser ($request);
            
            // make sure the chapter exists first
            $serieid = $request->getParameter ('serieid');
            $chapterid = $request->getParameter ('chapterid');
            
            $chapter = ChaptersQuery::create ()
                                    ->findOneById ((int) $chapterid);
            
            if ($chapter == null || $chapter->getIdSeries () != $serieid)
            {
                throw new \Exception ('The specified chapter doesn\'t exit');
            }
            
            $links = array ();
            
            // now generate the pages information
            for ($i = 1; $i <= $chapter->getPagesCount (); $i ++)
            {
                $links [] = '/live/' . $serieid . '/chapter/' . $chapterid . '/page/' . $i . '.jpg';
            }
            
            $request
                ->makeResponse ()
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($links)
                ->printOutput ();
        }
    };