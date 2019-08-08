<?php

namespace MangaSekai\Database;

use MangaSekai\Database\Base\Series as BaseSeries;

/**
 * Skeleton subclass for representing a row from the 'series' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Series extends BaseSeries
{
    const DEFAULT_IMAGE = '/app/img/logo_no_image.png';

    function getImage()
    {
        $image = parent::getImage ();

        if ($image == null || empty ($image))
        {
            return self::DEFAULT_IMAGE;
        }

        return $image;
    }

    function toArrayWithAuthorsAndGenres ()
    {
        $ourdata = $this->toArray ();
        $ourdata ['Authors'] = array ();
        $ourdata ['Genres'] = array ();

        $authors = SeriesStaffQuery::create ()
            ->filterByIdSerie ($this->getId ())
            ->addJoin (Map\SeriesStaffTableMap::COL_ID_STAFF, Map\StaffTableMap::COL_ID)
            ->withColumn (Map\StaffTableMap::COL_NAME, "Name")
            ->withColumn (Map\StaffTableMap::COL_IMAGE, "Image")
            ->find ();

        $genres = SeriesGenresQuery::create ()
            ->filterByIdSerie ($this->getId ())
            ->addJoin (Map\SeriesGenresTableMap::COL_ID_GENRE, Map\GenresTableMap::COL_ID)
            ->withColumn (Map\GenresTableMap::COL_NAME, "Name")
            ->find ();
        
        $ourdata ['Authors'] = $authors->toArray ();
        $ourdata ['Genres'] = $genres->toArray ();

        return $ourdata;
    }
}
