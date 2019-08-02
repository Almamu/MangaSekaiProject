<?php declare(strict_types=1);
    namespace MangaSekai\API;
    
    class ErrorCodes
    {
        const UNKNOWN_ERROR = 0;
        const CALL_MISSING_PARAMETERS = 50;
        const AUTHENTICATION_REQUIRED = 100;
        const AUTHENTICATION_FAILED = 101;
        const UNKNOWN_SERIES = 200;
        const UNKNOWN_CHAPTER = 300;
        const CANNOT_FIND_MATCH = 600;
        const UNKNOWN_IMAGE_FORMAT = 800;
        const UNKNOWN_PAGE = 400;
    };