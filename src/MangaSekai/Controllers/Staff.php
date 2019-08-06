<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;

    use \MangaSekai\Database\SeriesQuery;
    use \MangaSekai\Database\StaffQuery;

    class Staff
    {
        use \MangaSekai\Controllers\Security;

        function series (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            $series = SeriesQuery::create ()
                           ->filterByIdStaff ((int) $request->getParameter ('id'))
                           ->find ();
            $author = StaffQuery::create ()
                ->findOneById ((int) $request->getParameter ('id'));
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    array (
                        'Series' => $series->toArray (),
                        'Staff' => $author->toArray ()
                    )
                )
                ->printOutput ();
        }
    };