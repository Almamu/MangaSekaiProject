<?php

namespace MangaSekai\Database;

use MangaSekai\Database\Base\SeriesTrackerQuery as BaseSeriesTrackerQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'series_tracker' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SeriesTrackerQuery extends BaseSeriesTrackerQuery
{
    function toArrayWithChapters ()
    {
        $list = $this->find ();
        
        $output = array ();
        
        foreach ($list as $entry)
        {
            $chapterTrack = ChapterTrackerQuery::create ()
                ->leftJoinWithChapters ()
                ->useChaptersQuery()
                    ->filterByIdSeries ($entry->getIdSeries ())
                ->orderByNumber (\Propel\Runtime\ActiveQuery\Criteria::DESC)
                ->endUse ()
                ->findOne ();
            
            $element = $entry->toArray ();
            $element ['Series'] = $entry->getSeries ()->toArray ();
            $element ['ChapterTrack'] = $chapterTrack->toArray ();
            $element ['Chapter'] = $chapterTrack->getChapters ()->toArray ();
            
            $output [] = $element;
        }
        
        return $output;
    }
}
