<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\SeriesStaff as ChildSeriesStaff;
use MangaSekai\Database\SeriesStaffQuery as ChildSeriesStaffQuery;
use MangaSekai\Database\Map\SeriesStaffTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'series_staff' table.
 *
 *
 *
 * @method     ChildSeriesStaffQuery orderByIdSerie($order = Criteria::ASC) Order by the id_serie column
 * @method     ChildSeriesStaffQuery orderByIdStaff($order = Criteria::ASC) Order by the id_staff column
 * @method     ChildSeriesStaffQuery orderByRole($order = Criteria::ASC) Order by the role column
 *
 * @method     ChildSeriesStaffQuery groupByIdSerie() Group by the id_serie column
 * @method     ChildSeriesStaffQuery groupByIdStaff() Group by the id_staff column
 * @method     ChildSeriesStaffQuery groupByRole() Group by the role column
 *
 * @method     ChildSeriesStaffQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSeriesStaffQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSeriesStaffQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSeriesStaffQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSeriesStaffQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSeriesStaffQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSeriesStaff findOne(ConnectionInterface $con = null) Return the first ChildSeriesStaff matching the query
 * @method     ChildSeriesStaff findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSeriesStaff matching the query, or a new ChildSeriesStaff object populated from the query conditions when no match is found
 *
 * @method     ChildSeriesStaff findOneByIdSerie(int $id_serie) Return the first ChildSeriesStaff filtered by the id_serie column
 * @method     ChildSeriesStaff findOneByIdStaff(int $id_staff) Return the first ChildSeriesStaff filtered by the id_staff column
 * @method     ChildSeriesStaff findOneByRole(string $role) Return the first ChildSeriesStaff filtered by the role column *

 * @method     ChildSeriesStaff requirePk($key, ConnectionInterface $con = null) Return the ChildSeriesStaff by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeriesStaff requireOne(ConnectionInterface $con = null) Return the first ChildSeriesStaff matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeriesStaff requireOneByIdSerie(int $id_serie) Return the first ChildSeriesStaff filtered by the id_serie column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeriesStaff requireOneByIdStaff(int $id_staff) Return the first ChildSeriesStaff filtered by the id_staff column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeriesStaff requireOneByRole(string $role) Return the first ChildSeriesStaff filtered by the role column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeriesStaff[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSeriesStaff objects based on current ModelCriteria
 * @method     ChildSeriesStaff[]|ObjectCollection findByIdSerie(int $id_serie) Return ChildSeriesStaff objects filtered by the id_serie column
 * @method     ChildSeriesStaff[]|ObjectCollection findByIdStaff(int $id_staff) Return ChildSeriesStaff objects filtered by the id_staff column
 * @method     ChildSeriesStaff[]|ObjectCollection findByRole(string $role) Return ChildSeriesStaff objects filtered by the role column
 * @method     ChildSeriesStaff[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SeriesStaffQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\SeriesStaffQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\SeriesStaff', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSeriesStaffQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSeriesStaffQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSeriesStaffQuery) {
            return $criteria;
        }
        $query = new ChildSeriesStaffQuery();
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
     * @param array[$id_serie, $id_staff] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSeriesStaff|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SeriesStaffTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SeriesStaffTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildSeriesStaff A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_serie, id_staff, role FROM series_staff WHERE id_serie = :p0 AND id_staff = :p1';
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
            /** @var ChildSeriesStaff $obj */
            $obj = new ChildSeriesStaff();
            $obj->hydrate($row);
            SeriesStaffTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildSeriesStaff|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SeriesStaffTableMap::COL_ID_SERIE, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SeriesStaffTableMap::COL_ID_STAFF, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SeriesStaffTableMap::COL_ID_SERIE, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SeriesStaffTableMap::COL_ID_STAFF, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id_serie column
     *
     * Example usage:
     * <code>
     * $query->filterByIdSerie(1234); // WHERE id_serie = 1234
     * $query->filterByIdSerie(array(12, 34)); // WHERE id_serie IN (12, 34)
     * $query->filterByIdSerie(array('min' => 12)); // WHERE id_serie > 12
     * </code>
     *
     * @param     mixed $idSerie The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function filterByIdSerie($idSerie = null, $comparison = null)
    {
        if (is_array($idSerie)) {
            $useMinMax = false;
            if (isset($idSerie['min'])) {
                $this->addUsingAlias(SeriesStaffTableMap::COL_ID_SERIE, $idSerie['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idSerie['max'])) {
                $this->addUsingAlias(SeriesStaffTableMap::COL_ID_SERIE, $idSerie['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesStaffTableMap::COL_ID_SERIE, $idSerie, $comparison);
    }

    /**
     * Filter the query on the id_staff column
     *
     * Example usage:
     * <code>
     * $query->filterByIdStaff(1234); // WHERE id_staff = 1234
     * $query->filterByIdStaff(array(12, 34)); // WHERE id_staff IN (12, 34)
     * $query->filterByIdStaff(array('min' => 12)); // WHERE id_staff > 12
     * </code>
     *
     * @param     mixed $idStaff The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function filterByIdStaff($idStaff = null, $comparison = null)
    {
        if (is_array($idStaff)) {
            $useMinMax = false;
            if (isset($idStaff['min'])) {
                $this->addUsingAlias(SeriesStaffTableMap::COL_ID_STAFF, $idStaff['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idStaff['max'])) {
                $this->addUsingAlias(SeriesStaffTableMap::COL_ID_STAFF, $idStaff['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesStaffTableMap::COL_ID_STAFF, $idStaff, $comparison);
    }

    /**
     * Filter the query on the role column
     *
     * Example usage:
     * <code>
     * $query->filterByRole('fooValue');   // WHERE role = 'fooValue'
     * $query->filterByRole('%fooValue%', Criteria::LIKE); // WHERE role LIKE '%fooValue%'
     * </code>
     *
     * @param     string $role The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function filterByRole($role = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($role)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesStaffTableMap::COL_ROLE, $role, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSeriesStaff $seriesStaff Object to remove from the list of results
     *
     * @return $this|ChildSeriesStaffQuery The current query, for fluid interface
     */
    public function prune($seriesStaff = null)
    {
        if ($seriesStaff) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SeriesStaffTableMap::COL_ID_SERIE), $seriesStaff->getIdSerie(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SeriesStaffTableMap::COL_ID_STAFF), $seriesStaff->getIdStaff(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the series_staff table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesStaffTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeriesStaffTableMap::clearInstancePool();
            SeriesStaffTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesStaffTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SeriesStaffTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SeriesStaffTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SeriesStaffTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SeriesStaffQuery
