<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesGenresQuery;
    use \MangaSekai\Database\SeriesQuery;
    
    class Genres
    {
        use \MangaSekai\Controllers\Security;
        
        function series (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            $series = SeriesQuery::create ()
                ->useSeriesGenresQuery ()
                    ->filterByIdGenre ($request->getParameter ('id'))
                ->endUse ()
                ->find ();
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput ($series->toArray ())
                ->printOutput ();
        }
    };