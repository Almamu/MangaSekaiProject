<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\PagesQuery;
    
    class Page
    {
        use \MangaSekai\Controllers\Security;
    
        function get (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);

            /** @var boolean $isTemporal Indicates if a temporal file had to be created to access it */
            $isTemporal = false;
            
            $chapterId = $request->getParameter ('id');
            $pageNumber = $request->getParameter ('number');
            
            $page = PagesQuery::create ()
                              ->filterByIdChapter ($chapterId)
                              ->filterByPage ($pageNumber)
                              ->findOne ();
            
            if ($page == null)
                throw new \Exception ('Cannot find page', \MangaSekai\API\ErrorCodes::UNKNOWN_PAGE);

            $path = $page->getPath ();

            // first check if the page's path is special and extract the file off the container
            if (strpos ($page->getPath (), ":/") !== false && class_exists (\ZipArchive::class) === true)
            {
                $parts = explode (":/", $path);

                // create a temporal file as output
                $path = tempnam (sys_get_temp_dir (), 'mangasekai');

                $archive = new \ZipArchive;

                if ($archive->open ($parts [0]) == false)
                    throw new \Exception ("Cannot open the compressed zip file", \MangaSekai\API\ErrorCodes::UNABLE_TO_OPEN_FILE);

                // write the zip's contents to the temporal file
                file_put_contents ($path, $archive->getFromName ($parts [1]));

                // close the archive again
                $archive->close ();

                $isTemporal = true;
            }

            // find page first
            $imageType = exif_imagetype ($path);

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
                    throw new \Exception ('Unrecognized image format (' . $imageType . '). Cannot display image', \MangaSekai\API\ErrorCodes::UNKNOWN_IMAGE_FORMAT);
            }
            
            $response
                ->setOutput (file_get_contents ($path))
                ->printOutput ();

            // if the file was temporal, remove it off the disk
            if ($isTemporal == true)
                unlink ($path);
        }
    }