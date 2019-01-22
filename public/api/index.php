<?php declare(strict_types=1);

    require ('../../vendor/autoload.php');
    require ('../../config/config.php');
    
    if (getenv ('PROJECT_PATH') === false)
    {
        putenv ("PROJECT_PATH=" . dirname (dirname (realpath (__DIR__))) . '/');
    }
    
    if (getenv ('PAGE_PATH') === false)
    {
        putenv ('PAGE_PATH=' . getenv ('PROJECT_PATH') . 'public/live/%d/chapter/%d/page/%d.jpg');
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