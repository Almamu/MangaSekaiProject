<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\ChaptersQuery;
    use \MangaSekai\Database\SeriesQuery;
    
    class Installer
    {
        use \MangaSekai\Controllers\Security;
        
        function start (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $response->setContentType (\MangaSekai\HTTP\Response::JSON);
        }
        
        function upload (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            // we can discard the returned data from this function
            $this->validateUser ($request);
            $bodyData = $request->getBodyData ();
            
            if (
                array_key_exists ('pages', $bodyData) === false ||
                array_key_exists ('chapter', $bodyData) === false ||
                array_key_exists ('series', $bodyData) === false
            )
            {
                throw new \Exception ('The upload is missing information');
            }
            
            // check that the chapter and series id are correct
            $chapter = ChaptersQuery::create ()
                ->filterByIdSeries ($bodyData ['series'])
                ->filterById ($bodyData ['chapter'])
                ->findOne ();
    
            $serie = SeriesQuery::create ()
                                ->findOneById ($bodyData ['serieid']);
            
            if ($chapter === null || $serie === null)
            {
                throw new \Exception ('The chapter you\'re trying to upload files to doesn\'t exist');
            }
            
            $pages = $bodyData ['pages'];
            
            $i = 1;
            
            foreach ($pages as $content)
            {
                $information = array (
                    'tmpname' => tempnam (sys_get_temp_dir (), 'msp_'),
                    'finalname' => sprintf (getenv ('PAGE_PATH'), $serie->getId (), $chapter->getId (), $i)
                );
                
                // save the file
                file_put_contents ($information ['tmpname'], base64_decode ($content));
                
                // now get info with the images extension and convert it to JPG if necessary
                $imageType = exif_imagetype ($information ['tmpname']);
                
                // WARNING, GOTO's AND LABELS ARE EVIL, REALLY EVIL, PLEASE REFRAIN TO USE THEM UNLESS EXPLICITLY NEEDED LIKE HERE
                switch ($imageType)
                {
                    case IMAGETYPE_JPEG:
                        // simple save the file to the correct path
                        rename ($information ['tmpname'], $information ['finalname']);
                        break;
                        
                    case IMAGETYPE_PNG:
                        // perform conversion
                        $image = imagecreatefrompng ($information ['tmpname']);
                        goto saveimage;
                        
                    case IMAGETYPE_GIF:
                        $image = imagecreatefromgif ($information ['tmpname']);
                        goto saveimage;
                        
                    case IMAGETYPE_BMP:
                        $image = imagecreatefrombmp ($information ['tmpname']);
                        goto saveimage;
                        
                        saveimage: imagejpeg ($image, $information ['finalname'], 100);
                        break;
                        
                    default:
                        throw new \Exception ('Unrecognized image format (' . $imageType . '). Cannot perform chapter upload');
                }
                
                $i ++;
            }
            
            // save the chapter
            $chapter
                ->setPagesCount ($i)
                ->save ();
            
            // recalculate the number of chapters available for the series and save it
            $chapters = ChaptersQuery::create ()
                         ->findByIdSeries ($serie->getId ());
            
            $serie
                ->setChapterCount ($chapters->count ())
                ->save ();
            
            $response
                ->setContentType (\MangaSekai\HTTP\Response::JSON)
                ->setOutput (array ());
        }
    };