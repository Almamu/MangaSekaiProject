<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    class Installer
    {
        function start (\MangaSekai\HTTP\Request $request)
        {
            $response = $request->makeResponse ();
            $response->setContentType (\MangaSekai\HTTP\Response::JSON);
        }
    };