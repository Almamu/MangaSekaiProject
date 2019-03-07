<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesTrackerQuery;
    use \MangaSekai\Database\ChapterTrackerQuery;
    
    use \MangaSekai\Database\SeriesQuery;
    use \MangaSekai\Database\ChaptersQuery;
    
    use \MangaSekai\Database\ChapterTracker;
    use \MangaSekai\Database\SeriesTracker;
    
    class Track
    {
        use \MangaSekai\Controllers\Security;
        
        function series (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $storage = $this->validateUser ($request);
            
            if ($request->getMethod () == 'POST')
            {
                $serie = SeriesQuery::create ()
                    ->findOneById ($request->getBodyData () ['serieid']);
                
                if ($serie === null)
                {
                    throw new \Exception ('Cannot find the specified serie in the database');
                }
                
                $track = new SeriesTracker ();
                $track
                    ->setIdSeries ($request->getBodyData () ['serieid'])
                    ->setIdUser ($storage->get ('id'))
                    ->save ();
                
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (
                        $track->toArray ()
                    )
                    ->printOutput();
            }
            else
            {
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (
                        SeriesTrackerQuery::create ()
                            ->findByIdUser ($storage->get ('id'))
                    )
                    ->printOutput();
            }
        }
        
        function chapters (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $storage = $this->validateUser ($request);
            
            if ($request->getMethod () == 'POST')
            {
                // first search the chapter by ids
                $chapter = ChaptersQuery::create ()
                    ->filterByIdSeries ($request->getParameter ('id'))
                    ->filterById ($request->getBodyData() ['chapterid'])
                    ->findOne ();
                
                if ($chapter === null)
                {
                    throw new \Exception ('Cannot find the specified chapter in the database');
                }
                
                $track = new ChapterTracker ();
                $track
                    ->setIdChapter ($request->getBodyData () ['chapterid'])
                    ->setIdUser ($storage->get ('id'))
                    ->save ();
                
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput (
                        $track->toArray ()
                    )
                    ->printOutput();
            }
            else
            {
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput(
                        ChapterTrackerQuery::create ()
                            ->useChaptersQuery ()
                                ->filterByIdSeries ($request->getParameter ('id'))
                            ->endUse ()
                            ->filterByIdUser ($storage->get ('id'))
                        ->find ()
                    )
                    ->printOutput();
            }
        }
    };