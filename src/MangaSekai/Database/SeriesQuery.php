<?php

namespace MangaSekai\Database;

use MangaSekai\Database\Base\SeriesQuery as BaseSeriesQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'series' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SeriesQuery extends BaseSeriesQuery
{
    function filterByIdStaff (int $id)
    {
        return $this
            ->distinct()
            ->addJoin (Map\SeriesTableMap::COL_ID, Map\SeriesStaffTableMap::COL_ID_SERIE, Criteria::LEFT_JOIN)
            ->where (Map\SeriesStaffTableMap::COL_ID_STAFF . ' = ?', $id, \PDO::PARAM_INT);
    }
}
