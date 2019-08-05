<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\ChapterTracker as ChildChapterTracker;
use MangaSekai\Database\ChapterTrackerQuery as ChildChapterTrackerQuery;
use MangaSekai\Database\Map\ChapterTrackerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chapter_tracker' table.
 *
 *
 *
 * @method     ChildChapterTrackerQuery orderByIdChapter($order = Criteria::ASC) Order by the id_chapter column
 * @method     ChildChapterTrackerQuery orderByIdUser($order = Criteria::ASC) Order by the id_user column
 * @method     ChildChapterTrackerQuery orderByPage($order = Criteria::ASC) Order by the page column
 *
 * @method     ChildChapterTrackerQuery groupByIdChapter() Group by the id_chapter column
 * @method     ChildChapterTrackerQuery groupByIdUser() Group by the id_user column
 * @method     ChildChapterTrackerQuery groupByPage() Group by the page column
 *
 * @method     ChildChapterTrackerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChapterTrackerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChapterTrackerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChapterTrackerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildChapterTrackerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildChapterTrackerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildChapterTrackerQuery leftJoinChapters($relationAlias = null) Adds a LEFT JOIN clause to the query using the Chapters relation
 * @method     ChildChapterTrackerQuery rightJoinChapters($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Chapters relation
 * @method     ChildChapterTrackerQuery innerJoinChapters($relationAlias = null) Adds a INNER JOIN clause to the query using the Chapters relation
 *
 * @method     ChildChapterTrackerQuery joinWithChapters($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Chapters relation
 *
 * @method     ChildChapterTrackerQuery leftJoinWithChapters() Adds a LEFT JOIN clause and with to the query using the Chapters relation
 * @method     ChildChapterTrackerQuery rightJoinWithChapters() Adds a RIGHT JOIN clause and with to the query using the Chapters relation
 * @method     ChildChapterTrackerQuery innerJoinWithChapters() Adds a INNER JOIN clause and with to the query using the Chapters relation
 *
 * @method     ChildChapterTrackerQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildChapterTrackerQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildChapterTrackerQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildChapterTrackerQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildChapterTrackerQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildChapterTrackerQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildChapterTrackerQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     \MangaSekai\Database\ChaptersQuery|\MangaSekai\Database\UsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildChapterTracker findOne(ConnectionInterface $con = null) Return the first ChildChapterTracker matching the query
 * @method     ChildChapterTracker findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChapterTracker matching the query, or a new ChildChapterTracker object populated from the query conditions when no match is found
 *
 * @method     ChildChapterTracker findOneByIdChapter(int $id_chapter) Return the first ChildChapterTracker filtered by the id_chapter column
 * @method     ChildChapterTracker findOneByIdUser(int $id_user) Return the first ChildChapterTracker filtered by the id_user column
 * @method     ChildChapterTracker findOneByPage(int $page) Return the first ChildChapterTracker filtered by the page column *

 * @method     ChildChapterTracker requirePk($key, ConnectionInterface $con = null) Return the ChildChapterTracker by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapterTracker requireOne(ConnectionInterface $con = null) Return the first ChildChapterTracker matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChapterTracker requireOneByIdChapter(int $id_chapter) Return the first ChildChapterTracker filtered by the id_chapter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapterTracker requireOneByIdUser(int $id_user) Return the first ChildChapterTracker filtered by the id_user column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChapterTracker requireOneByPage(int $page) Return the first ChildChapterTracker filtered by the page column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChapterTracker[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildChapterTracker objects based on current ModelCriteria
 * @method     ChildChapterTracker[]|ObjectCollection findByIdChapter(int $id_chapter) Return ChildChapterTracker objects filtered by the id_chapter column
 * @method     ChildChapterTracker[]|ObjectCollection findByIdUser(int $id_user) Return ChildChapterTracker objects filtered by the id_user column
 * @method     ChildChapterTracker[]|ObjectCollection findByPage(int $page) Return ChildChapterTracker objects filtered by the page column
 * @method     ChildChapterTracker[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ChapterTrackerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \MangaSekai\Database\Base\ChapterTrackerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\MangaSekai\\Database\\ChapterTracker', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChapterTrackerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChapterTrackerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildChapterTrackerQuery) {
            return $criteria;
        }
        $query = new ChildChapterTrackerQuery();
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
     * @param array[$id_chapter, $id_user] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildChapterTracker|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChapterTrackerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ChapterTrackerTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildChapterTracker A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_chapter, id_user, page FROM chapter_tracker WHERE id_chapter = :p0 AND id_user = :p1';
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
            /** @var ChildChapterTracker $obj */
            $obj = new ChildChapterTracker();
            $obj->hydrate($row);
            ChapterTrackerTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildChapterTracker|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ChapterTrackerTableMap::COL_ID_CHAPTER, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ChapterTrackerTableMap::COL_ID_USER, $key[1], Criteria::EQUAL);
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
     * @see       filterByChapters()
     *
     * @param     mixed $idChapter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByIdChapter($idChapter = null, $comparison = null)
    {
        if (is_array($idChapter)) {
            $useMinMax = false;
            if (isset($idChapter['min'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $idChapter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idChapter['max'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $idChapter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $idChapter, $comparison);
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
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $idUser, $comparison);
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
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByPage($page = null, $comparison = null)
    {
        if (is_array($page)) {
            $useMinMax = false;
            if (isset($page['min'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_PAGE, $page['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($page['max'])) {
                $this->addUsingAlias(ChapterTrackerTableMap::COL_PAGE, $page['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChapterTrackerTableMap::COL_PAGE, $page, $comparison);
    }

    /**
     * Filter the query by a related \MangaSekai\Database\Chapters object
     *
     * @param \MangaSekai\Database\Chapters|ObjectCollection $chapters The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByChapters($chapters, $comparison = null)
    {
        if ($chapters instanceof \MangaSekai\Database\Chapters) {
            return $this
                ->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $chapters->getId(), $comparison);
        } elseif ($chapters instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChapterTrackerTableMap::COL_ID_CHAPTER, $chapters->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
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
     * Filter the query by a related \MangaSekai\Database\Users object
     *
     * @param \MangaSekai\Database\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \MangaSekai\Database\Users) {
            return $this
                ->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChapterTrackerTableMap::COL_ID_USER, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
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
     * @param   ChildChapterTracker $chapterTracker Object to remove from the list of results
     *
     * @return $this|ChildChapterTrackerQuery The current query, for fluid interface
     */
    public function prune($chapterTracker = null)
    {
        if ($chapterTracker) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ChapterTrackerTableMap::COL_ID_CHAPTER), $chapterTracker->getIdChapter(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ChapterTrackerTableMap::COL_ID_USER), $chapterTracker->getIdUser(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chapter_tracker table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChapterTrackerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChapterTrackerTableMap::clearInstancePool();
            ChapterTrackerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ChapterTrackerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChapterTrackerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ChapterTrackerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChapterTrackerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ChapterTrackerQuery
