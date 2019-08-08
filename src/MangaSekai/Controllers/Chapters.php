<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\ChaptersQuery;
    use \MangaSekai\Database\PagesQuery;
    
    class Chapters
    {
        use \MangaSekai\JSON\PaginationResponse;
        use \MangaSekai\Controllers\Security;
    
        function list (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $page = 1;
        
            if ($request->hasQueryStringParameter ('page') === true)
            {
                $page = $request->getQueryStringParameter ('page');
            }
        
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    ChaptersQuery::create ()
                                 ->filterByIdSeries ($request->getParameter ('id'))
                                 ->orderByNumber ()
                                 ->find ()
                                 ->toArray ()
                )
                ->printOutput ();
        }
        
        function pages (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            // make sure the chapter exists first
            $serieid = $request->getParameter ('serieid');
            $chapterid = $request->getParameter ('chapterid');
            
            $chapter = ChaptersQuery::create ()
                                    ->findOneById ((int) $chapterid);
            $previous = null;
            $next = null;

            if ($chapter == null || $chapter->getIdSeries () != $serieid)
            {
                throw new \Exception ('The specified chapter doesn\'t exit', \MangaSekai\API\ErrorCodes::UNKNOWN_CHAPTER);
            }

            if ($chapter->getNumber () > 1)
            {
                $previous = ChaptersQuery::create ()
                                         ->filterByIdSeries ($chapter->getIdSeries ())
                                         ->filterByNumber ($chapter->getNumber() - 1)
                                         ->findOne ();
            }

            $next = ChaptersQuery::create ()
                                 ->filterByIdSeries ($chapter->getIdSeries ())
                                 ->filterByNumber ($chapter->getNumber() + 1)
                                 ->findOne ();

            if ($next)
                $next = $next->toArray ();

            if ($previous)
                $previous = $previous->toArray ();

            $links = array ();
            $pages = PagesQuery::create ()
                                ->orderByPage ()
                                ->findByIdChapter ($chapter->getId ());
            
            // now generate the pages information
            foreach ($pages as $page)
            {
                $links [] = '/api/index.php/chapter/' . $chapterid . '/page/' . $page->getPage () . '/';
            }

            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'pages' => $links,
                        'chapter' => $chapter->toArray (),
                        'serie' => $chapter->getSeries ()->toArray (),
                        'next' => $next,
                        'previous' => $previous
                    )
                )
                ->printOutput ();
        }
    };