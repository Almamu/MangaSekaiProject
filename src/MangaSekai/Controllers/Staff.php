<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;

    use \MangaSekai\Database\SeriesQuery;

    class Staff
    {
        use \MangaSekai\Controllers\Security;

        function series (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (
                    SeriesQuery::create ()
                        ->filterByIdStaff ((int) $request->getParameter ('id'))
                        ->find ()
                        ->toArray ()
                )
                ->printOutput ();
        }
    };