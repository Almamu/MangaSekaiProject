<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\Pages as ChildPages;
use MangaSekai\Database\PagesQuery as ChildPagesQuery;
use MangaSekai\Database\Map\PagesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pages' table.
 *
 *
 *
 * @method     ChildPagesQuery orderByIdChapter($order = Criteria::ASC) Order by the id_chapter column
 * @method     ChildPagesQuery orderByPage($order = Criteria::ASC) Order by the page column
 * @method     ChildPagesQuery orderByPath($order = Criteria::ASC) Order by the path column
 *
 * @method     ChildPagesQuery groupByIdChapter() Group by the id_chapter column
 * @method     ChildPagesQuery groupByPage() Group by the page column
 * @method     ChildPagesQuery groupByPath() Group by the path column
 *
 * @method     ChildPagesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPagesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPagesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPagesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPagesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPagesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPages findOne(ConnectionInterface $con = null) Return the first ChildPages matching the query
 * @method     ChildPages findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPages matching the query, or a new ChildPages object populated from the query conditions when no match is found
 *
 * @method     ChildPages findOneByIdChapter(int $id_chapter) Return the first ChildPages filtered by the id_chapter column
 * @method     ChildPages findOneByPage(int $page) Return the first ChildPages filtered by the page column
 * @method     ChildPages findOneByPath(string $path) Return the first ChildPages filtered by the path column *

 * @method     ChildPages requirePk($key, ConnectionInterface $con = null) Return the ChildPages by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPages requireOne(ConnectionInterface $con = null) Return the first ChildPages matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPages requireOneByIdChapter(int $id_chapter) Return the first ChildPages filtered by the id_chapter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPages requireOneByPage(int $page) Return the first ChildPages filtered by the page column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPages requireOneByPath(string $path) Return the first ChildPages filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPages[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPages objects based on current ModelCriteria
 * @method     ChildPages[]|ObjectCollection findByIdChapter(int $id_chapter) Return ChildPages objects filtered by the id_chapter column
 * @method     ChildPages[]|ObjectCollection findByPage(int $page) Return ChildPages objects filtered by the page column
 * @method     ChildPages[]|ObjectCollection findByPath(string $path) Return ChildPages objects filtered by the path column
 * @method     ChildPages[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PagesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\PagesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\Pages', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPagesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPagesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPagesQuery) {
            return $criteria;
        }
        $query = new ChildPagesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id_chapter, $page] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPages|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PagesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PagesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPages A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_chapter, page, path FROM pages WHERE id_chapter = :p0 AND page = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPages $obj */
            $obj = new ChildPages();
            $obj->hydrate($row);
            PagesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPages|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PagesTableMap::COL_ID_CHAPTER, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PagesTableMap::COL_PAGE, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PagesTableMap::COL_ID_CHAPTER, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PagesTableMap::COL_PAGE, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id_chapter column
     *
     * Example usage:
     * <code>
     * $query->filterByIdChapter(1234); // WHERE id_chapter = 1234
     * $query->filterByIdChapter(array(12, 34)); // WHERE id_chapter IN (12, 34)
     * $query->filterByIdChapter(array('min' => 12)); // WHERE id_chapter > 12
     * </code>
     *
     * @param     mixed $idChapter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function filterByIdChapter($idChapter = null, $comparison = null)
    {
        if (is_array($idChapter)) {
            $useMinMax = false;
            if (isset($idChapter['min'])) {
                $this->addUsingAlias(PagesTableMap::COL_ID_CHAPTER, $idChapter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idChapter['max'])) {
                $this->addUsingAlias(PagesTableMap::COL_ID_CHAPTER, $idChapter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagesTableMap::COL_ID_CHAPTER, $idChapter, $comparison);
    }

    /**
     * Filter the query on the page column
     *
     * Example usage:
     * <code>
     * $query->filterByPage(1234); // WHERE page = 1234
     * $query->filterByPage(array(12, 34)); // WHERE page IN (12, 34)
     * $query->filterByPage(array('min' => 12)); // WHERE page > 12
     * </code>
     *
     * @param     mixed $page The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function filterByPage($page = null, $comparison = null)
    {
        if (is_array($page)) {
            $useMinMax = false;
            if (isset($page['min'])) {
                $this->addUsingAlias(PagesTableMap::COL_PAGE, $page['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($page['max'])) {
                $this->addUsingAlias(PagesTableMap::COL_PAGE, $page['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagesTableMap::COL_PAGE, $page, $comparison);
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
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PagesTableMap::COL_PATH, $path, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPages $pages Object to remove from the list of results
     *
     * @return $this|ChildPagesQuery The current query, for fluid interface
     */
    public function prune($pages = null)
    {
        if ($pages) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PagesTableMap::COL_ID_CHAPTER), $pages->getIdChapter(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PagesTableMap::COL_PAGE), $pages->getPage(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pages table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PagesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PagesTableMap::clearInstancePool();
            PagesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PagesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PagesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PagesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PagesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PagesQuery
