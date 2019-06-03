<?php declare(strict_types=1);
    return array (
        '/upload/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Installer',
            'function' => 'upload'
        ),
        '/series/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Series',
            'function' => 'list'
        ),
        '/series/:id/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Series',
            'function' => 'info'
        ),
        '/series/:id/chapters/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Chapters',
            'function' => 'list'
        ),
        '/series/:serieid/chapter/:chapterid/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Chapters',
            'function' => 'pages'
        ),
        '/login/' => array (
            'controller' => '\\MangaSekai\\Controllers\\User',
            'function' => 'login'
        ),
        '/token/refresh/' => array (
            'controller' => '\\MangaSekai\\Controllers\\User',
            'function' => 'refresh'
        ),
        '/track/series/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Track',
            'function' => 'series'
        ),
        '/track/series/:id/chapters/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Track',
            'function' => 'chapters'
        ),
        '/match/search/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Match',
            'function' => 'search'
        ),
        '/files/' => array (
            'controller' => '\\MangaSekai\\Controllers\\Files',
            'function' => 'search'
        )
    );