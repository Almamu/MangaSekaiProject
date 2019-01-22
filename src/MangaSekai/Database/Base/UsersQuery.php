<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\Users as ChildUsers;
use MangaSekai\Database\UsersQuery as ChildUsersQuery;
use MangaSekai\Database\Map\UsersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users' table.
 *
 *
 *
 * @method     ChildUsersQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUsersQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUsersQuery orderByPassword($order = Criteria::ASC) Order by the password column
 *
 * @method     ChildUsersQuery groupById() Group by the id column
 * @method     ChildUsersQuery groupByUsername() Group by the username column
 * @method     ChildUsersQuery groupByPassword() Group by the password column
 *
 * @method     ChildUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUsersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUsersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUsersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUsersQuery leftJoinChapterTracker($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChapterTracker relation
 * @method     ChildUsersQuery rightJoinChapterTracker($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChapterTracker relation
 * @method     ChildUsersQuery innerJoinChapterTracker($relationAlias = null) Adds a INNER JOIN clause to the query using the ChapterTracker relation
 *
 * @method     ChildUsersQuery joinWithChapterTracker($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ChapterTracker relation
 *
 * @method     ChildUsersQuery leftJoinWithChapterTracker() Adds a LEFT JOIN clause and with to the query using the ChapterTracker relation
 * @method     ChildUsersQuery rightJoinWithChapterTracker() Adds a RIGHT JOIN clause and with to the query using the ChapterTracker relation
 * @method     ChildUsersQuery innerJoinWithChapterTracker() Adds a INNER JOIN clause and with to the query using the ChapterTracker relation
 *
 * @method     ChildUsersQuery leftJoinSeriesTracker($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeriesTracker relation
 * @method     ChildUsersQuery rightJoinSeriesTracker($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeriesTracker relation
 * @method     ChildUsersQuery innerJoinSeriesTracker($relationAlias = null) Adds a INNER JOIN clause to the query using the SeriesTracker relation
 *
 * @method     ChildUsersQuery joinWithSeriesTracker($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SeriesTracker relation
 *
 * @method     ChildUsersQuery leftJoinWithSeriesTracker() Adds a LEFT JOIN clause and with to the query using the SeriesTracker relation
 * @method     ChildUsersQuery rightJoinWithSeriesTracker() Adds a RIGHT JOIN clause and with to the query using the SeriesTracker relation
 * @method     ChildUsersQuery innerJoinWithSeriesTracker() Adds a INNER JOIN clause and with to the query using the SeriesTracker relation
 *
 * @method     \MangaSekai\Database\ChapterTrackerQuery|\MangaSekai\Database\SeriesTrackerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUsers findOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query
 * @method     ChildUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUsers matching the query, or a new ChildUsers object populated from the query conditions when no match is found
 *
 * @method     ChildUsers findOneById(int $id) Return the first ChildUsers filtered by the id column
 * @method     ChildUsers findOneByUsername(string $username) Return the first ChildUsers filtered by the username column
 * @method     ChildUsers findOneByPassword(string $password) Return the first ChildUsers filtered by the password column *

 * @method     ChildUsers requirePk($key, ConnectionInterface $con = null) Return the ChildUsers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers requireOneById(int $id) Return the first ChildUsers filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByUsername(string $username) Return the first ChildUsers filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByPassword(string $password) Return the first ChildUsers filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUsers objects based on current ModelCriteria
 * @method     ChildUsers[]|ObjectCollection findById(int $id) Return ChildUsers objects filtered by the id column
 * @method     ChildUsers[]|ObjectCollection findByUsername(string $username) Return ChildUsers objects filtered by the username column
 * @method     ChildUsers[]|ObjectCollection findByPassword(string $password) Return ChildUsers objects filtered by the password column
 * @method     ChildUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UsersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\UsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\Users', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUsersQuery) {
            return $criteria;
        }
        $query = new ChildUsersQuery();
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UsersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UsersTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, username, password FROM users WHERE id = :p0';
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
            /** @var ChildUsers $obj */
            $obj = new ChildUsers();
            $obj->hydrate($row);
            UsersTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UsersTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UsersTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UsersTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UsersTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query by a related \MangaSekai\Database\ChapterTracker object
     *
     * @param \MangaSekai\Database\ChapterTracker|ObjectCollection $chapterTracker the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByChapterTracker($chapterTracker, $comparison = null)
    {
        if ($chapterTracker instanceof \MangaSekai\Database\ChapterTracker) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $chapterTracker->getIdUser(), $comparison);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * Filter the query by a related \MangaSekai\Database\SeriesTracker object
     *
     * @param \MangaSekai\Database\SeriesTracker|ObjectCollection $seriesTracker the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterBySeriesTracker($seriesTracker, $comparison = null)
    {
        if ($seriesTracker instanceof \MangaSekai\Database\SeriesTracker) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $seriesTracker->getIdUser(), $comparison);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * @param   ChildUsers $users Object to remove from the list of results
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function prune($users = null)
    {
        if ($users) {
            $this->addUsingAlias(UsersTableMap::COL_ID, $users->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UsersTableMap::clearInstancePool();
            UsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UsersQuery
