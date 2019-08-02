<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\Chapters as ChildChapters;
use MangaSekai\Database\ChaptersQuery as ChildChaptersQuery;
use MangaSekai\Database\Map\ChaptersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chapters' table.
 *
 *
 *
 * @method     ChildChaptersQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChaptersQuery orderByIdSeries($order = Criteria::ASC) Order by the id_series column
 * @method     ChildChaptersQuery orderByPagesCount($order = Criteria::ASC) Order by the pages_count column
 * @method     ChildChaptersQuery orderByNumber($order = Criteria::ASC) Order by the number column
 *
 * @method     ChildChaptersQuery groupById() Group by the id column
 * @method     ChildChaptersQuery groupByIdSeries() Group by the id_series column
 * @method     ChildChaptersQuery groupByPagesCount() Group by the pages_count column
 * @method     ChildChaptersQuery groupByNumber() Group by the number column
 *
 * @method     ChildChaptersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChaptersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChaptersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChaptersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildChaptersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildChaptersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildChaptersQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method     ChildChaptersQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method     ChildChaptersQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method     ChildChaptersQuery joinWithSeries($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Series relation
 *
 * @method     ChildChaptersQuery leftJoinWithSeries() Adds a LEFT JOIN clause and with to the query using the Series relation
 * @method     ChildChaptersQuery rightJoinWithSeries() Adds a RIGHT JOIN clause and with to the query using the Series relation
 * @method     ChildChaptersQuery innerJoinWithSeries() Adds a INNER JOIN clause and with to the query using the Series relation
 *
 * @method     ChildChaptersQuery leftJoinChapterTracker($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChapterTracker relation
 * @method     ChildChaptersQuery rightJoinChapterTracker($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChapterTracker relation
 * @method     ChildChaptersQuery innerJoinChapterTracker($relationAlias = null) Adds a INNER JOIN clause to the query using the ChapterTracker relation
 *
 * @method     ChildChaptersQuery joinWithChapterTracker($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ChapterTracker relation
 *
 * @method     ChildChaptersQuery leftJoinWithChapterTracker() Adds a LEFT JOIN clause and with to the query using the ChapterTracker relation
 * @method     ChildChaptersQuery rightJoinWithChapterTracker() Adds a RIGHT JOIN clause and with to the query using the ChapterTracker relation
 * @method     ChildChaptersQuery innerJoinWithChapterTracker() Adds a INNER JOIN clause and with to the query using the ChapterTracker relation
 *
 * @method     \MangaSekai\Database\SeriesQuery|\MangaSekai\Database\ChapterTrackerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildChapters findOne(ConnectionInterface $con = null) Return the first ChildChapters matching the query
 * @method     ChildChapters findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChapters matching the query, or a new ChildChapters object populated from the query conditions when no match is found
 *
 * @method     ChildChapters findOneById(int $id) Return the first ChildChapters filtered by the id column
 * @method     ChildChapters findOneByIdSeries(int $id_series) Return the first ChildChapters filtered by the id_series column
 * @method     ChildChapters findOneByPagesCount(int $pages_count) Return the first ChildChapters filtered by the pages_count column
 * @method     ChildChapters findOneByNumber(double $number) Return the first ChildChapters filtered by the number column *

 * @method     ChildChapters requirePk($key, ConnectionInterface $con = null) Return the ChildChapters by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapters requireOne(ConnectionInterface $con = null) Return the first ChildChapters matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChapters requireOneById(int $id) Return the first ChildChapters filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapters requireOneByIdSeries(int $id_series) Return the first ChildChapters filtered by the id_series column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapters requireOneByPagesCount(int $pages_count) Return the first ChildChapters filtered by the pages_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapters requireOneByNumber(double $number) Return the first ChildChapters filtered by the number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChapters[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildChapters objects based on current ModelCriteria
 * @method     ChildChapters[]|ObjectCollection findById(int $id) Return ChildChapters objects filtered by the id column
 * @method     ChildChapters[]|ObjectCollection findByIdSeries(int $id_series) Return ChildChapters objects filtered by the id_series column
 * @method     ChildChapters[]|ObjectCollection findByPagesCount(int $pages_count) Return ChildChapters objects filtered by the pages_count column
 * @method     ChildChapters[]|ObjectCollection findByNumber(double $number) Return ChildChapters objects filtered by the number column
 * @method     ChildChapters[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ChaptersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\ChaptersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\Chapters', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChaptersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChaptersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildChaptersQuery) {
            return $criteria;
        }
        $query = new ChildChaptersQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildChapters|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChaptersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ChaptersTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildChapters A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, id_series, pages_count, number FROM chapters WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildChapters $obj */
            $obj = new ChildChapters();
            $obj->hydrate($row);
            ChaptersTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildChapters|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChaptersTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChaptersTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChaptersTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the id_series column
     *
     * Example usage:
     * <code>
     * $query->filterByIdSeries(1234); // WHERE id_series = 1234
     * $query->filterByIdSeries(array(12, 34)); // WHERE id_series IN (12, 34)
     * $query->filterByIdSeries(array('min' => 12)); // WHERE id_series > 12
     * </code>
     *
     * @see       filterBySeries()
     *
     * @param     mixed $idSeries The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByIdSeries($idSeries = null, $comparison = null)
    {
        if (is_array($idSeries)) {
            $useMinMax = false;
            if (isset($idSeries['min'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_ID_SERIES, $idSeries['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idSeries['max'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_ID_SERIES, $idSeries['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChaptersTableMap::COL_ID_SERIES, $idSeries, $comparison);
    }

    /**
     * Filter the query on the pages_count column
     *
     * Example usage:
     * <code>
     * $query->filterByPagesCount(1234); // WHERE pages_count = 1234
     * $query->filterByPagesCount(array(12, 34)); // WHERE pages_count IN (12, 34)
     * $query->filterByPagesCount(array('min' => 12)); // WHERE pages_count > 12
     * </code>
     *
     * @param     mixed $pagesCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByPagesCount($pagesCount = null, $comparison = null)
    {
        if (is_array($pagesCount)) {
            $useMinMax = false;
            if (isset($pagesCount['min'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_PAGES_COUNT, $pagesCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pagesCount['max'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_PAGES_COUNT, $pagesCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChaptersTableMap::COL_PAGES_COUNT, $pagesCount, $comparison);
    }

    /**
     * Filter the query on the number column
     *
     * Example usage:
     * <code>
     * $query->filterByNumber(1234); // WHERE number = 1234
     * $query->filterByNumber(array(12, 34)); // WHERE number IN (12, 34)
     * $query->filterByNumber(array('min' => 12)); // WHERE number > 12
     * </code>
     *
     * @param     mixed $number The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByNumber($number = null, $comparison = null)
    {
        if (is_array($number)) {
            $useMinMax = false;
            if (isset($number['min'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_NUMBER, $number['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($number['max'])) {
                $this->addUsingAlias(ChaptersTableMap::COL_NUMBER, $number['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChaptersTableMap::COL_NUMBER, $number, $comparison);
    }

    /**
     * Filter the query by a related \MangaSekai\Database\Series object
     *
     * @param \MangaSekai\Database\Series|ObjectCollection $series The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildChaptersQuery The current query, for fluid interface
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof \MangaSekai\Database\Series) {
            return $this
                ->addUsingAlias(ChaptersTableMap::COL_ID_SERIES, $series->getId(), $comparison);
        } elseif ($series instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChaptersTableMap::COL_ID_SERIES, $series->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySeries() only accepts arguments of type \MangaSekai\Database\Series or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Series relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function joinSeries($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Series');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Series');
        }

        return $this;
    }

    /**
     * Use the Series relation Series object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\SeriesQuery A secondary query class using the current class as primary query
     */
    public function useSeriesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Series', '\MangaSekai\Database\SeriesQuery');
    }

    /**
     * Filter the query by a related \MangaSekai\Database\ChapterTracker object
     *
     * @param \MangaSekai\Database\ChapterTracker|ObjectCollection $chapterTracker the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChaptersQuery The current query, for fluid interface
     */
    public function filterByChapterTracker($chapterTracker, $comparison = null)
    {
        if ($chapterTracker instanceof \MangaSekai\Database\ChapterTracker) {
            return $this
                ->addUsingAlias(ChaptersTableMap::COL_ID, $chapterTracker->getIdChapter(), $comparison);
        } elseif ($chapterTracker instanceof ObjectCollection) {
            return $this
                ->useChapterTrackerQuery()
                ->filterByPrimaryKeys($chapterTracker->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChapterTracker() only accepts arguments of type \MangaSekai\Database\ChapterTracker or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChapterTracker relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function joinChapterTracker($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChapterTracker');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ChapterTracker');
        }

        return $this;
    }

    /**
     * Use the ChapterTracker relation ChapterTracker object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\ChapterTrackerQuery A secondary query class using the current class as primary query
     */
    public function useChapterTrackerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChapterTracker($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChapterTracker', '\MangaSekai\Database\ChapterTrackerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChapters $chapters Object to remove from the list of results
     *
     * @return $this|ChildChaptersQuery The current query, for fluid interface
     */
    public function prune($chapters = null)
    {
        if ($chapters) {
            $this->addUsingAlias(ChaptersTableMap::COL_ID, $chapters->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chapters table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChaptersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChaptersTableMap::clearInstancePool();
            ChaptersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChaptersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChaptersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ChaptersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChaptersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ChaptersQuery
