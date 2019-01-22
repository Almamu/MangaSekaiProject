<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\SeriesTracker as ChildSeriesTracker;
use MangaSekai\Database\SeriesTrackerQuery as ChildSeriesTrackerQuery;
use MangaSekai\Database\Map\SeriesTrackerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'series_tracker' table.
 *
 *
 *
 * @method     ChildSeriesTrackerQuery orderByIdUser($order = Criteria::ASC) Order by the id_user column
 * @method     ChildSeriesTrackerQuery orderByIdSeries($order = Criteria::ASC) Order by the id_series column
 *
 * @method     ChildSeriesTrackerQuery groupByIdUser() Group by the id_user column
 * @method     ChildSeriesTrackerQuery groupByIdSeries() Group by the id_series column
 *
 * @method     ChildSeriesTrackerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSeriesTrackerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSeriesTrackerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSeriesTrackerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSeriesTrackerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSeriesTrackerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSeriesTrackerQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method     ChildSeriesTrackerQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method     ChildSeriesTrackerQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method     ChildSeriesTrackerQuery joinWithSeries($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Series relation
 *
 * @method     ChildSeriesTrackerQuery leftJoinWithSeries() Adds a LEFT JOIN clause and with to the query using the Series relation
 * @method     ChildSeriesTrackerQuery rightJoinWithSeries() Adds a RIGHT JOIN clause and with to the query using the Series relation
 * @method     ChildSeriesTrackerQuery innerJoinWithSeries() Adds a INNER JOIN clause and with to the query using the Series relation
 *
 * @method     ChildSeriesTrackerQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildSeriesTrackerQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildSeriesTrackerQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildSeriesTrackerQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildSeriesTrackerQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildSeriesTrackerQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildSeriesTrackerQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     \MangaSekai\Database\SeriesQuery|\MangaSekai\Database\UsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSeriesTracker findOne(ConnectionInterface $con = null) Return the first ChildSeriesTracker matching the query
 * @method     ChildSeriesTracker findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSeriesTracker matching the query, or a new ChildSeriesTracker object populated from the query conditions when no match is found
 *
 * @method     ChildSeriesTracker findOneByIdUser(int $id_user) Return the first ChildSeriesTracker filtered by the id_user column
 * @method     ChildSeriesTracker findOneByIdSeries(int $id_series) Return the first ChildSeriesTracker filtered by the id_series column *

 * @method     ChildSeriesTracker requirePk($key, ConnectionInterface $con = null) Return the ChildSeriesTracker by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeriesTracker requireOne(ConnectionInterface $con = null) Return the first ChildSeriesTracker matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeriesTracker requireOneByIdUser(int $id_user) Return the first ChildSeriesTracker filtered by the id_user column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeriesTracker requireOneByIdSeries(int $id_series) Return the first ChildSeriesTracker filtered by the id_series column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeriesTracker[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSeriesTracker objects based on current ModelCriteria
 * @method     ChildSeriesTracker[]|ObjectCollection findByIdUser(int $id_user) Return ChildSeriesTracker objects filtered by the id_user column
 * @method     ChildSeriesTracker[]|ObjectCollection findByIdSeries(int $id_series) Return ChildSeriesTracker objects filtered by the id_series column
 * @method     ChildSeriesTracker[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SeriesTrackerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\SeriesTrackerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\SeriesTracker', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSeriesTrackerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSeriesTrackerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSeriesTrackerQuery) {
            return $criteria;
        }
        $query = new ChildSeriesTrackerQuery();
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
     * @param array[$id_user, $id_series] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSeriesTracker|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SeriesTrackerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SeriesTrackerTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildSeriesTracker A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_user, id_series FROM series_tracker WHERE id_user = :p0 AND id_series = :p1';
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
            /** @var ChildSeriesTracker $obj */
            $obj = new ChildSeriesTracker();
            $obj->hydrate($row);
            SeriesTrackerTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildSeriesTracker|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SeriesTrackerTableMap::COL_ID_USER, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SeriesTrackerTableMap::COL_ID_SERIES, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id_user column
     *
     * Example usage:
     * <code>
     * $query->filterByIdUser(1234); // WHERE id_user = 1234
     * $query->filterByIdUser(array(12, 34)); // WHERE id_user IN (12, 34)
     * $query->filterByIdUser(array('min' => 12)); // WHERE id_user > 12
     * </code>
     *
     * @see       filterByUsers()
     *
     * @param     mixed $idUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $idUser, $comparison);
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
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterByIdSeries($idSeries = null, $comparison = null)
    {
        if (is_array($idSeries)) {
            $useMinMax = false;
            if (isset($idSeries['min'])) {
                $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $idSeries['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idSeries['max'])) {
                $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $idSeries['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $idSeries, $comparison);
    }

    /**
     * Filter the query by a related \MangaSekai\Database\Series object
     *
     * @param \MangaSekai\Database\Series|ObjectCollection $series The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof \MangaSekai\Database\Series) {
            return $this
                ->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $series->getId(), $comparison);
        } elseif ($series instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SeriesTrackerTableMap::COL_ID_SERIES, $series->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
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
     * Filter the query by a related \MangaSekai\Database\Users object
     *
     * @param \MangaSekai\Database\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \MangaSekai\Database\Users) {
            return $this
                ->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SeriesTrackerTableMap::COL_ID_USER, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \MangaSekai\Database\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Users');

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
            $this->addJoinObject($join, 'Users');
        }

        return $this;
    }

    /**
     * Use the Users relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MangaSekai\Database\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\MangaSekai\Database\UsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSeriesTracker $seriesTracker Object to remove from the list of results
     *
     * @return $this|ChildSeriesTrackerQuery The current query, for fluid interface
     */
    public function prune($seriesTracker = null)
    {
        if ($seriesTracker) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SeriesTrackerTableMap::COL_ID_USER), $seriesTracker->getIdUser(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SeriesTrackerTableMap::COL_ID_SERIES), $seriesTracker->getIdSeries(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the series_tracker table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTrackerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeriesTrackerTableMap::clearInstancePool();
            SeriesTrackerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTrackerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SeriesTrackerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SeriesTrackerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SeriesTrackerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SeriesTrackerQuery
