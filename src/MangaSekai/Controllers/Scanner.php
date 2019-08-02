<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;
    
    use \MangaSekai\Database\SeriesQuery;
    
    class Scanner
    {
        use \MangaSekai\Controllers\Security;
        
        public function scan (\MangaSekai\HTTP\Request $request, \MangaSekai\HTTP\Response $response)
        {
            $this->validateUser ($request);
            $scanner = new \MangaSekai\Scanners\FileScanner ();
            
            $scanner->scan ();
            
            // now update all the descriptions
            $series = SeriesQuery::create ()->find ();
            
            foreach ($series as $serie)
            {
                $matcher = new \MangaSekai\AniList\Matcher ();
                $list = $matcher->match ($serie->getName ());
                
                if (count ($list) == 0)
                    continue;
                
                $entry = reset ($list);
                
                // update the image if needed
                if ($serie->getImage () == null)
                {
                    // create temporal file
                    $filename = tempnam (sys_get_temp_dir (), 'mangasekai');
                    file_put_contents ($filename, file_get_contents ($entry->getCover ()));
                    
                    // find page first
                    $imageType = exif_imagetype ($filename);
                    $mimeType = '';
    
                    switch ($imageType)
                    {
                        case IMAGETYPE_JPEG:
                            $mimeType = 'image/jpeg';
                            break;
        
                        case IMAGETYPE_PNG:
                            $mimeType = 'image/png';
                            break;
        
                        case IMAGETYPE_GIF:
                            $mimeType = 'image/gif';
                            break;
        
                        case IMAGETYPE_BMP:
                            $mimeType = 'image/bmp';
                            break;
        
                        default:
                            throw new \Exception ('Unrecognized image format (' . $imageType . '). Cannot perform chapter upload', \MangaSekai\API\ErrorCodes::UNKNOWN_IMAGE_FORMAT);
                    }
                    
                    $serie->setImage (
                        'data:' . $mimeType . ';base64,' . base64_encode (file_get_contents ($filename))
                    );
                    
                    unlink ($filename);
                }
                
                $serie
                    ->setName ($entry->getName ())
                    ->setDescription ($entry->getDescription ())
                    ->save ();
            }
        }
    };