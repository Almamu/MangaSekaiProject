<?php declare(strict_types=1);
    return array (
        '/installer' => array (
            'controller' => '\\MangaSekai\\Controllers\\Installer',
            'function' => 'start'
        ),
        '/series' => array (
            'controller' => '\\MangaSekai\\Controllers\\Series',
            'function' => 'list'
        ),
        '/series/:id' => array (
            'controller' => '\\MangaSekai\\Controllers\\Series',
            'function' => 'info',
            'parameters' => array (
                ':id' => '[0-9]+'
            )
        ),
        '/series/:id/chapters' => array (
            'controller' => '\\MangaSekai\\Controllers\\Chapters',
            'function' => 'list',
            'parameters' => array (
                ':id' => '[0-9]+'
            )
        ),
        '/series/:serieid/chapter/:chapterid' => array (
            'controller' => '\\MangaSekai\\Controllers\\Chapters',
            'function' => 'pages',
            'parameters' => array (
                ':serieid' => '[0-9]+',
                ':chapterid' => '[0-9]+'
            )
        )
    );