<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\Series as ChildSeries;
use MangaSekai\Database\SeriesQuery as ChildSeriesQuery;
use MangaSekai\Database\Map\SeriesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'series' table.
 *
 *
 *
 * @method     ChildSeriesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSeriesQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildSeriesQuery orderByChapterCount($order = Criteria::ASC) Order by the chapter_count column
 * @method     ChildSeriesQuery orderByPagesCount($order = Criteria::ASC) Order by the pages_count column
 * @method     ChildSeriesQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildSeriesQuery orderBySynced($order = Criteria::ASC) Order by the synced column
 * @method     ChildSeriesQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method     ChildSeriesQuery orderByPath($order = Criteria::ASC) Order by the path column
 *
 * @method     ChildSeriesQuery groupById() Group by the id column
 * @method     ChildSeriesQuery groupByName() Group by the name column
 * @method     ChildSeriesQuery groupByChapterCount() Group by the chapter_count column
 * @method     ChildSeriesQuery groupByPagesCount() Group by the pages_count column
 * @method     ChildSeriesQuery groupByDescription() Group by the description column
 * @method     ChildSeriesQuery groupBySynced() Group by the synced column
 * @method     ChildSeriesQuery groupByImage() Group by the image column
 * @method     ChildSeriesQuery groupByPath() Group by the path column
 *
 * @method     ChildSeriesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSeriesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSeriesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSeriesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSeriesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSeriesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSeriesQuery leftJoinChapters($relationAlias = null) Adds a LEFT JOIN clause to the query using the Chapters relation
 * @method     ChildSeriesQuery rightJoinChapters($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Chapters relation
 * @method     ChildSeriesQuery innerJoinChapters($relationAlias = null) Adds a INNER JOIN clause to the query using the Chapters relation
 *
 * @method     ChildSeriesQuery joinWithChapters($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Chapters relation
 *
 * @method     ChildSeriesQuery leftJoinWithChapters() Adds a LEFT JOIN clause and with to the query using the Chapters relation
 * @method     ChildSeriesQuery rightJoinWithChapters() Adds a RIGHT JOIN clause and with to the query using the Chapters relation
 * @method     ChildSeriesQuery innerJoinWithChapters() Adds a INNER JOIN clause and with to the query using the Chapters relation
 *
 * @method     ChildSeriesQuery leftJoinSeriesGenres($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeriesGenres relation
 * @method     ChildSeriesQuery rightJoinSeriesGenres($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeriesGenres relation
 * @method     ChildSeriesQuery innerJoinSeriesGenres($relationAlias = null) Adds a INNER JOIN clause to the query using the SeriesGenres relation
 *
 * @method     ChildSeriesQuery joinWithSeriesGenres($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SeriesGenres relation
 *
 * @method     ChildSeriesQuery leftJoinWithSeriesGenres() Adds a LEFT JOIN clause and with to the query using the SeriesGenres relation
 * @method     ChildSeriesQuery rightJoinWithSeriesGenres() Adds a RIGHT JOIN clause and with to the query using the SeriesGenres relation
 * @method     ChildSeriesQuery innerJoinWithSeriesGenres() Adds a INNER JOIN clause and with to the query using the SeriesGenres relation
 *
 * @method     ChildSeriesQuery leftJoinSeriesTracker($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeriesTracker relation
 * @method     ChildSeriesQuery rightJoinSeriesTracker($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeriesTracker relation
 * @method     ChildSeriesQuery innerJoinSeriesTracker($relationAlias = null) Adds a INNER JOIN clause to the query using the SeriesTracker relation
 *
 * @method     ChildSeriesQuery joinWithSeriesTracker($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SeriesTracker relation
 *
 * @method     ChildSeriesQuery leftJoinWithSeriesTracker() Adds a LEFT JOIN clause and with to the query using the SeriesTracker relation
 * @method     ChildSeriesQuery rightJoinWithSeriesTracker() Adds a RIGHT JOIN clause and with to the query using the SeriesTracker relation
 * @method     ChildSeriesQuery innerJoinWithSeriesTracker() Adds a INNER JOIN clause and with to the query using the SeriesTracker relation
 *
 * @method     \MangaSekai\Database\ChaptersQuery|\MangaSekai\Database\SeriesGenresQuery|\MangaSekai\Database\SeriesTrackerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSeries findOne(ConnectionInterface $con = null) Return the first ChildSeries matching the query
 * @method     ChildSeries findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSeries matching the query, or a new ChildSeries object populated from the query conditions when no match is found
 *
 * @method     ChildSeries findOneById(int $id) Return the first ChildSeries filtered by the id column
 * @method     ChildSeries findOneByName(string $name) Return the first ChildSeries filtered by the name column
 * @method     ChildSeries findOneByChapterCount(int $chapter_count) Return the first ChildSeries filtered by the chapter_count column
 * @method     ChildSeries findOneByPagesCount(int $pages_count) Return the first ChildSeries filtered by the pages_count column
 * @method     ChildSeries findOneByDescription(string $description) Return the first ChildSeries filtered by the description column
 * @method     ChildSeries findOneBySynced(int $synced) Return the first ChildSeries filtered by the synced column
 * @method     ChildSeries findOneByImage(string $image) Return the first ChildSeries filtered by the image column
 * @method     ChildSeries findOneByPath(string $path) Return the first ChildSeries filtered by the path column *

 * @method     ChildSeries requirePk($key, ConnectionInterface $con = null) Return the ChildSeries by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOne(ConnectionInterface $con = null) Return the first ChildSeries matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeries requireOneById(int $id) Return the first ChildSeries filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByName(string $name) Return the first ChildSeries filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByChapterCount(int $chapter_count) Return the first ChildSeries filtered by the chapter_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByPagesCount(int $pages_count) Return the first ChildSeries filtered by the pages_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByDescription(string $description) Return the first ChildSeries filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneBySynced(int $synced) Return the first ChildSeries filtered by the synced column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByImage(string $image) Return the first ChildSeries filtered by the image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeries requireOneByPath(string $path) Return the first ChildSeries filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeries[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSeries objects based on current ModelCriteria
 * @method     ChildSeries[]|ObjectCollection findById(int $id) Return ChildSeries objects filtered by the id column
 * @method     ChildSeries[]|ObjectCollection findByName(string $name) Return ChildSeries objects filtered by the name column
 * @method     ChildSeries[]|ObjectCollection findByChapterCount(int $chapter_count) Return ChildSeries objects filtered by the chapter_count column
 * @method     ChildSeries[]|ObjectCollection findByPagesCount(int $pages_count) Return ChildSeries objects filtered by the pages_count column
 * @method     ChildSeries[]|ObjectCollection findByDescription(string $description) Return ChildSeries objects filtered by the description column
 * @method     ChildSeries[]|ObjectCollection findBySynced(int $synced) Return ChildSeries objects filtered by the synced column
 * @method     ChildSeries[]|ObjectCollection findByImage(string $image) Return ChildSeries objects filtered by the image column
 * @method     ChildSeries[]|ObjectCollection findByPath(string $path) Return ChildSeries objects filtered by the path column
 * @method     ChildSeries[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SeriesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\SeriesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\Series', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSeriesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSeriesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSeriesQuery) {
            return $criteria;
        }
        $query = new ChildSeriesQuery();
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
     * @return ChildSeries|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SeriesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SeriesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSeries A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, chapter_count, pages_count, description, synced, image, path FROM series WHERE id = :p0';
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
            /** @var ChildSeries $obj */
            $obj = new ChildSeries();
            $obj->hydrate($row);
            SeriesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSeries|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeriesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeriesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SeriesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SeriesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the chapter_count column
     *
     * Example usage:
     * <code>
     * $query->filterByChapterCount(1234); // WHERE chapter_count = 1234
     * $query->filterByChapterCount(array(12, 34)); // WHERE chapter_count IN (12, 34)
     * $query->filterByChapterCount(array('min' => 12)); // WHERE chapter_count > 12
     * </code>
     *
     * @param     mixed $chapterCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByChapterCount($chapterCount = null, $comparison = null)
    {
        if (is_array($chapterCount)) {
            $useMinMax = false;
            if (isset($chapterCount['min'])) {
                $this->addUsingAlias(SeriesTableMap::COL_CHAPTER_COUNT, $chapterCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($chapterCount['max'])) {
                $this->addUsingAlias(SeriesTableMap::COL_CHAPTER_COUNT, $chapterCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_CHAPTER_COUNT, $chapterCount, $comparison);
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
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByPagesCount($pagesCount = null, $comparison = null)
    {
        if (is_array($pagesCount)) {
            $useMinMax = false;
            if (isset($pagesCount['min'])) {
                $this->addUsingAlias(SeriesTableMap::COL_PAGES_COUNT, $pagesCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pagesCount['max'])) {
                $this->addUsingAlias(SeriesTableMap::COL_PAGES_COUNT, $pagesCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_PAGES_COUNT, $pagesCount, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the synced column
     *
     * Example usage:
     * <code>
     * $query->filterBySynced(1234); // WHERE synced = 1234
     * $query->filterBySynced(array(12, 34)); // WHERE synced IN (12, 34)
     * $query->filterBySynced(array('min' => 12)); // WHERE synced > 12
     * </code>
     *
     * @param     mixed $synced The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterBySynced($synced = null, $comparison = null)
    {
        if (is_array($synced)) {
            $useMinMax = false;
            if (isset($synced['min'])) {
                $this->addUsingAlias(SeriesTableMap::COL_SYNCED, $synced['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($synced['max'])) {
                $this->addUsingAlias(SeriesTableMap::COL_SYNCED, $synced['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_SYNCED, $synced, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%', Criteria::LIKE); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the path column
     *
     * Example usage:
     * <code>
     * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
     * $query->filterByPath('%fooValue%', Criteria::LIKE); // WHERE path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $path The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTableMap::COL_PATH, $path, $comparison);
    }

    /**
     * Filter the query by a related \MangaSekai\Database\Chapters object
     *
     * @param \MangaSekai\Database\Chapters|ObjectCollection $chapters the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeriesQuery The current query, for fluid interface
     */
    public function filterByChapters($chapters, $comparison = null)
    {
        if ($chapters instanceof \MangaSekai\Database\Chapters) {
            return $this
                ->addUsingAlias(SeriesTableMap::COL_ID, $chapters->getIdSeries(), $comparison);
        } elseif ($chapters instanceof ObjectCollection) {
            return $this
                ->useChaptersQuery()
                ->filterByPrimaryKeys($chapters->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChapters() only accepts arguments of type \MangaSekai\Database\Chapters or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Chapters relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function joinChapters($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Chapters');

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
            $this->addJoinObject($join, 'Chapters');
        }

        return $this;
    }

    /**
     * Use the Chapters relation Chapters object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\ChaptersQuery A secondary query class using the current class as primary query
     */
    public function useChaptersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChapters($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Chapters', '\MangaSekai\Database\ChaptersQuery');
    }

    /**
     * Filter the query by a related \MangaSekai\Database\SeriesGenres object
     *
     * @param \MangaSekai\Database\SeriesGenres|ObjectCollection $seriesGenres the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeriesQuery The current query, for fluid interface
     */
    public function filterBySeriesGenres($seriesGenres, $comparison = null)
    {
        if ($seriesGenres instanceof \MangaSekai\Database\SeriesGenres) {
            return $this
                ->addUsingAlias(SeriesTableMap::COL_ID, $seriesGenres->getIdSerie(), $comparison);
        } elseif ($seriesGenres instanceof ObjectCollection) {
            return $this
                ->useSeriesGenresQuery()
                ->filterByPrimaryKeys($seriesGenres->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeriesGenres() only accepts arguments of type \MangaSekai\Database\SeriesGenres or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeriesGenres relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function joinSeriesGenres($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeriesGenres');

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
            $this->addJoinObject($join, 'SeriesGenres');
        }

        return $this;
    }

    /**
     * Use the SeriesGenres relation SeriesGenres object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\SeriesGenresQuery A secondary query class using the current class as primary query
     */
    public function useSeriesGenresQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeriesGenres($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeriesGenres', '\MangaSekai\Database\SeriesGenresQuery');
    }

    /**
     * Filter the query by a related \MangaSekai\Database\SeriesTracker object
     *
     * @param \MangaSekai\Database\SeriesTracker|ObjectCollection $seriesTracker the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeriesQuery The current query, for fluid interface
     */
    public function filterBySeriesTracker($seriesTracker, $comparison = null)
    {
        if ($seriesTracker instanceof \MangaSekai\Database\SeriesTracker) {
            return $this
                ->addUsingAlias(SeriesTableMap::COL_ID, $seriesTracker->getIdSeries(), $comparison);
        } elseif ($seriesTracker instanceof ObjectCollection) {
            return $this
                ->useSeriesTrackerQuery()
                ->filterByPrimaryKeys($seriesTracker->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeriesTracker() only accepts arguments of type \MangaSekai\Database\SeriesTracker or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeriesTracker relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function joinSeriesTracker($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeriesTracker');

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
            $this->addJoinObject($join, 'SeriesTracker');
        }

        return $this;
    }

    /**
     * Use the SeriesTracker relation SeriesTracker object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\SeriesTrackerQuery A secondary query class using the current class as primary query
     */
    public function useSeriesTrackerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeriesTracker($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeriesTracker', '\MangaSekai\Database\SeriesTrackerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSeries $series Object to remove from the list of results
     *
     * @return $this|ChildSeriesQuery The current query, for fluid interface
     */
    public function prune($series = null)
    {
        if ($series) {
            $this->addUsingAlias(SeriesTableMap::COL_ID, $series->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the series table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeriesTableMap::clearInstancePool();
            SeriesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SeriesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SeriesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SeriesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SeriesQuery
