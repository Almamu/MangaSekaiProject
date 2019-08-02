<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\PagesQuery;
    
    class Page
    {
        use \MangaSekai\Controllers\Security;
    
        function get (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            
            $chapterId = $request->getParameter ('id');
            $pageNumber = $request->getParameter ('number');
            
            $page = PagesQuery::create ()
                              ->filterByIdChapter ($chapterId)
                              ->filterByPage ($pageNumber)
                              ->findOne ();
            
            if ($page == null)
            {
                throw new \Exception ('Cannot find page', \MangaSekai\API\ErrorCodes::UNKNOWN_PAGE);
            }
            
            // find page first
            $imageType = exif_imagetype ($page->getPath ());
    
            switch ($imageType)
            {
                case IMAGETYPE_JPEG:
                    $response->setContentType ('image/jpeg');
                    break;
        
                case IMAGETYPE_PNG:
                    $response->setContentType ('image/png');
                    break;
        
                case IMAGETYPE_GIF:
                    $response->setContentType ('image/gif');
                    break;
        
                case IMAGETYPE_BMP:
                    $response->setContentType ('image/bmp');
                    break;
        
                default:
                    throw new \Exception ('Unrecognized image format (' . $imageType . '). Cannot perform chapter upload', \MangaSekai\API\ErrorCodes::UNKNOWN_IMAGE_FORMAT);
            }
            
            $response
                ->setOutput (file_get_contents ($page->getPath ()))
                ->printOutput ();
        }
    }