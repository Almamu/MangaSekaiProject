<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesGenresQuery;
    use \MangaSekai\Database\SeriesQuery;
    use \MangaSekai\Database\GenresQuery;
    
    class Genres
    {
        use \MangaSekai\Controllers\Security;
        
        function series (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            $genre = GenresQuery::create ()->findOneById ((int) $request->getParameter ('id'));
            
            $series = SeriesQuery::create ()
                ->useSeriesGenresQuery ()
                    ->filterByIdGenre ($request->getParameter ('id'))
                ->endUse ()
                ->find ();
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (array ('Genre' => $genre->toArray (), 'Series' => $series->toArray ()))
                ->printOutput ();
        }
    };