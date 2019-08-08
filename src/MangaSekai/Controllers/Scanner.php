<?php declare(strict_types=1);
    namespace MangaSekai\Controllers;

    use \MangaSekai\Database\StaffQuery;
    use \MangaSekai\Database\SeriesQuery;
    use \MangaSekai\Database\SeriesStaffQuery;
    use \MangaSekai\Database\GenresQuery;
    use \MangaSekai\Database\SeriesGenresQuery;
    
    class Scanner
    {
        use \MangaSekai\Controllers\Security;

        private function downloadImage (string $url)
        {
            // create temporal file
            $filename = tempnam (sys_get_temp_dir (), 'mangasekai');
            file_put_contents ($filename, file_get_contents ($url));

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

            $result = 'data:' . $mimeType . ';base64,' . base64_encode (file_get_contents ($filename));

            unlink ($filename);

            return $result;
        }
        
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
                if ($serie->getImage () == \MangaSekai\Database\Series::DEFAULT_IMAGE)
                {
                    $serie->setImage (
                        $this->downloadImage ($entry->getCover ())
                    );
                }

                $serie
                    ->setName ($entry->getName ())
                    ->setDescription ($entry->getDescription ())
                    ->save ();

                // check that authors exist
                foreach ($entry->getExtraInfo () as $info)
                {
                    $staff = StaffQuery::create ()->findOneByName ($info ['name']);

                    if ($staff == null)
                    {
                        $staff = new \MangaSekai\Database\Staff ();
                        $staff
                            ->setName ($info ['name'])
                            ->setImage ($this->downloadImage ($info ['image']))
                            ->setDescription ($info ['description'])
                            ->save ();
                    }

                    $seriesStaffEntry = SeriesStaffQuery::create ()
                        ->filterByIdSerie ($serie->getId ())
                        ->filterByIdStaff ($staff->getId ())
                        ->findOne ();

                    if ($seriesStaffEntry == null)
                    {
                        $seriesStaffEntry = new \MangaSekai\Database\SeriesStaff ();
                        $seriesStaffEntry
                            ->setIdSerie ($serie->getId ())
                            ->setIdStaff ($staff->getId ())
                            ->setRole ($info ['role'])
                            ->save ();
                    }
                }
                
                foreach ($entry->getGenres () as $name)
                {
                    $genre = GenresQuery::create ()->findOneByName ($name);
                    
                    if ($genre == null)
                    {
                        $genre = new \MangaSekai\Database\Genres ();
    
                        $genre
                            ->setName ($name)
                            ->save ();
                    }
                    
                    $serieGenreEntry = SeriesGenresQuery::create ()
                        ->filterByIdSerie ($serie->getId ())
                        ->filterByIdGenre ($genre->getId ())
                        ->findOne ();
                    
                    if ($serieGenreEntry == null)
                    {
                        $serieGenreEntry = new \MangaSekai\Database\SeriesGenres ();
                        $serieGenreEntry
                            ->setIdGenre ($genre->getId ())
                            ->setIdSerie ($serie->getId ())
                            ->save ();
                    }
                }
            }
        }
    };