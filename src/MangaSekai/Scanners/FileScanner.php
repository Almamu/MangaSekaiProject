<?php declare(strict_types=1);
    namespace MangaSekai\Scanners;
    
    use \MangaSekai\Database\SettingsQuery;
    
    use \MangaSekai\Database\Series;
    use \MangaSekai\Database\SeriesQuery;
    
    use \MangaSekai\Database\Chapters;
    use \MangaSekai\Database\ChaptersQuery;
    
    use \MangaSekai\Database\Pages;
    use \MangaSekai\Database\PagesQuery;
    
    class FileScanner implements Scanner
    {
        /**
         * {@inheritdoc}
         */
        function scan (): void
        {
            $folders = SettingsQuery::create ()->findOneByName ('scanner_dirs')->getValue () ?? '';
            $series = array ();
            
            if (is_array ($folders) == false)
                return;
            
            foreach ($folders as $folder)
            {
                $series = array_merge_recursive ($series, $this->scanFolder ($folder));
            }
            
            // create series
            foreach ($series as $name => $serie)
            {
                $serieEntry = SeriesQuery::create ()->findOneByPath ($name);
                
                if ($serieEntry == null)
                {
                    $serieEntry = new Series ();
                    $serieEntry
                        ->setName ($name)
                        ->setChapterCount (0)
                        ->setPagesCount (0)
                        ->setDescription ('')
                        ->setPath ($name);
                }
    
                // save the entry
                $serieEntry->save ();
                
                // create chapter entries
                foreach ($serie ['chapters'] as $number => $pages)
                {
                    // check if chapter exists
                    $chapterEntry = ChaptersQuery::create ()
                        ->filterByIdSeries ($serieEntry->getId ())
                        ->filterByNumber ($number)
                        ->findOne ();
                    
                    if ($chapterEntry == null)
                    {
                        $chapterEntry = new Chapters ();
                        $chapterEntry
                            ->setIdSeries ($serieEntry->getId ())
                            ->setNumber ($number);
                    }
                    
                    // update page count and save the chapter info
                    $chapterEntry
                        ->setPagesCount (count ($pages))
                        ->save ();
                    
                    PagesQuery::create ()->filterByIdChapter ($chapterEntry->getId ())->delete ();
                    
                    foreach ($pages as $pageNumber => $path)
                    {
                        $page = new Pages ();
                        $page
                            ->setIdChapter ($chapterEntry->getId ())
                            ->setPage ($pageNumber)
                            ->setPath ($path)
                            ->save ();
                    }
                }
                
                // remove chapters no longer present
                $chapterlist = ChaptersQuery::create ()->findByIdSeries ($serieEntry->getId ());
                
                foreach ($chapterlist as $chapter)
                {
                    if (array_key_exists ((string) $chapter->getNumber (), $serie ['chapters']) == false)
                    {
                        $chapter->delete ();
                    }
                }
                
                // find actual chapter count and update the serie record
                $serieEntry
                    ->setChapterCount (
                        ChaptersQuery::create ()
                                     ->findByIdSeries (
                                         $serieEntry->getId ()
                                     )
                                     ->count ()
                    )
                    ->save ();
            }
            
            // remove non-existant series
            $seriesEntry = SeriesQuery::create ()->find ();
            
            foreach ($seriesEntry as $entry)
            {
                if (array_key_exists ($entry->getPath (), $series) === false)
                {
                    // find all the chapters for this series and delete the chapters and the pages
                    $chapters = ChaptersQuery::create()->findByIdSeries ($entry->getId ());
                    
                    foreach ($chapters as $chapter)
                    {
                        PagesQuery::create ()->findByIdChapter ($chapter->getId ())->delete ();
                    }
                    
                    $chapters->delete ();
                    $entry->delete ();
                }
            }
        }
        
        private function scanFolder (string $folder)
        {
            if (file_exists ($folder) == false)
                return array ();
            
            $dir = opendir ($folder);
            $series = array ();
            
            while (($entry = readdir ($dir)) !== false)
            {
                if ($entry == '.' || $entry == '..')
                    continue;
                
                $path = realpath ($folder) . '/' . $entry;

                // if the path has a .zip extension at the end then the entry should have that stripped off
                if (strpos ($path, ".zip") == (strlen ($path) - strlen (".zip")))
                {
                    $entry = substr ($entry, 0, strpos ($entry, ".zip"));

                    $series [$entry] = array (
                        'chapters' => $this->scanZipSerie ($path, $entry)
                    );
                }
                elseif (is_dir ($path) == true)
                    $series [$entry] = array (
                        'chapters' => $this->scanSerie ($path, $entry)
                    );
            }
            
            closedir ($dir);
            return $series;
        }
        
        private function scanSerie (string $folder, string $serieName)
        {
            $dir = opendir ($folder);
            $chapters = array ();
            
            while (($entry = readdir ($dir)) !== false)
            {
                if ($entry == '.' || $entry == '..')
                    continue;
                // extract number of chapter from the entry name
                if (preg_match ('/[0-9.]+/', $entry, $matches) == 0)
                    continue;
    
                $path = realpath ($folder) . '/' . $entry;
    
                // only scan zips and folders
                if (is_dir ($path) == true)
                    $chapters [(string) ((float) reset ($matches))] = $this->scanChapter ($path, (float) reset ($matches));
                elseif (strpos ($path, ".zip") == (strlen ($path) - strlen (".zip")))
                    $chapters [(string) ((float) reset ($matches))] = $this->scanZipChapter ($path, (float) reset ($matches));
            }
    
            closedir ($dir);
            return $chapters;
        }

        private function scanZipSerie (string $zipfile, string $serieName)
        {
            $chapters = array ();

            // open the zip file
            $zip = new \ZipArchive;
            $zip->open ($zipfile);

            $count = $zip->count ();

            // get the number of files available on the zip and iterate through them
            for ($i = 0; $i < $count; $i ++)
            {
                $entry = $zip->getNameIndex ($i);

                // ignore __MACOSX entries
                if (strpos ($entry, "__MACOSX/") === 0)
                    continue;
                if (preg_match_all ('/[0-9]+/', $entry, $matches) < 2)
                    continue;

                $chapterNumber = (string) ((float) $matches [0] [0]);
                $pageNumber = (int) end ($matches [0]);

                if (array_key_exists ($chapterNumber, $chapters) === false)
                    $chapters [$chapterNumber] = array ();

                $chapters [$chapterNumber] [$pageNumber] = realpath ($zipfile) . ':/' . $entry;
            }

            return $chapters;
        }
        
        private function scanChapter (string $folder, float $chapter)
        {
            $dir = opendir ($folder);
            $pages = array ();
            
            while (($entry = readdir ($dir)) !== false)
            {
                if ($entry == '.' || $entry == '..')
                    continue;
                if (preg_match_all ('/[0-9]+/', $entry, $matches) == 0)
                    continue;
                if (count ($matches [0]) == 0)
                    continue;
                
                $pages [(int) end ($matches [0])] = realpath ($folder) . '/' . $entry;
            }
            
            closedir ($dir);
            return $pages;
        }

        private function scanZipChapter (string $archive, float $chapter)
        {
            $pages = array ();

            // ensure the zip extension is installed first
            if (class_exists (\ZipArchive::class) === false)
                return array ();

            $zip = new \ZipArchive;
            $zip->open ($archive);

            // get the number of files available on the zip and iterate through them
            $count = $zip->count ();

            for ($i = 0; $i < $count; $i ++)
            {
                $entry = $zip->getNameIndex ($i);

                // ignore __MACOSX entries
                if (strpos ($entry, "__MACOSX/") === 0)
                    continue;
                if (preg_match_all ('/[0-9]+/', $entry, $matches) == 0)
                    continue;
                if (count ($matches [0]) == 0)
                    continue;

                $pages [(int) end ($matches [0])] = realpath ($archive) . ":/" . $entry;
            }

            $zip->close ();

            return $pages;
        }
    };