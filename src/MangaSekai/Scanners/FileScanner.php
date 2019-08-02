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
                $serieEntry = SeriesQuery::create ()->findOneByName ($name);
                
                if ($serieEntry == null)
                {
                    $serieEntry = new Series ();
                    $serieEntry
                        ->setName ($name)
                        ->setChapterCount (0)
                        ->setPagesCount (0)
                        ->setDescription ('');
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
                    
                    $pagesEntry = PagesQuery::create ()->findByIdChapter ($chapterEntry->getId ());
                    
                    // delete all the pages and add new
                    if ($pagesEntry->count () != 0)
                    {
                        $pagesEntry->delete ();
                    }
                    
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
                if (array_key_exists ($entry->getName (), $series) === false)
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
                
                // ignore non-folders here
                if (is_dir ($path) == false)
                    continue;
                
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
                preg_match ('/[0-9.]+/', $entry, $matches);
                
                // this chapter doesn't include any number in it
                if (count ($matches) == 0)
                    continue;
    
                $path = realpath ($folder) . '/' . $entry;
    
                // ignore non-folders here
                if (is_dir ($path) == false)
                    continue;
    
                $chapters [(string) ((float) reset ($matches))] = $this->scanChapter ($path, (float) reset ($matches));
            }
    
            closedir ($dir);
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
                
                preg_match ('/[0-9]+/', $entry, $matches);
                
                if (count ($matches) == 0)
                    continue;
                
                $pages [(int) reset ($matches)] = realpath ($folder) . '/' . $entry;
            }
            
            closedir ($dir);
            return $pages;
        }
    };