<?php declare(strict_types=1);

    require ('../vendor/autoload.php');
    require ('../config/config.php');
    
    if (getenv ('PROJECT_PATH') === false)
    {
        putenv ("PROJECT_PATH=" . dirname (realpath (__DIR__)) . '/');
    }
    
    $path = $_SERVER ['PATH_INFO'] ?? '/installer';
    
    try
    {
        \MangaSekai\API\Routing\Resolver::makeFromConfig ()->resolve ($path);
    }
    catch (\Exception $ex)
    {
        var_dump ($ex);
    }