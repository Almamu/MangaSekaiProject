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
                    throw new \Exception ('Cannot find the specified serie in the database', \MangaSekai\API\ErrorCodes::UNKNOWN_SERIES);
                }
                
                // check if the serie is already tracked
                $track = SeriesTrackerQuery::create ()
                    ->filterByIdSeries ($request->getBodyData () ['serieid'])
                    ->filterByIdUser ($storage->get ('id'))
                    ->findOne ();
                
                if ($track == null)
                {
                    $track = new SeriesTracker ();
                    $track
                        ->setIdSeries ($request->getBodyData () ['serieid'])
                        ->setIdUser ($storage->get ('id'))
                        ->save ();
                }
                
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
                            ->leftJoinWithSeries ()
                            ->filterByIdUser ($storage->get ('id'))
                            ->toArrayWithChapters ()
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
                    throw new \Exception ('Cannot find the specified chapter in the database', \MangaSekai\API\ErrorCodes::UNKNOWN_CHAPTER);
                }
                
                $track = ChapterTrackerQuery::create ()
                    ->filterByIdUser ($storage->get ('id'))
                    ->filterByIdChapter ($request->getBodyData () ['chapterid'])
                    ->findOne ();
                
                // ensure that the track record exists
                if ($track == null)
                {
                    $track = new ChapterTracker ();
                    $track
                        ->setIdChapter ($request->getBodyData () ['chapterid'])
                        ->setIdUser ($storage->get ('id'));
                }
                
                // update page
                $track
                    ->setPage ($request->getBodyData () ['page'])
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
                $chapters = ChapterTrackerQuery::create ()
                               ->useChaptersQuery ()
                                   ->filterByIdSeries ($request->getParameter ('id'))
                               ->endUse ()
                               ->leftJoinChapters ()
                               ->filterByIdUser ($storage->get ('id'));
                
                if ($request->hasParameter ('chapterid') == true)
                {
                    $chapters = $chapters
                        ->filterByIdChapter ($request->getParameter ('chapterid'))
                        ->findOne ()
                        ->toArray ();
                    
                    if ($chapters == null)
                    {
                        $chapters = array ();
                    }
                }
                else
                {
                    $chapters = $chapters->find ()->toArray ();
                }
                    
                $response
                    ->setContentType (\MangaSekai\HTTP\Response::JSON)
                    ->setOutput($chapters)
                    ->printOutput();
            }
        }
    };