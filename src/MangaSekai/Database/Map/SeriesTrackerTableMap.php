<?php

namespace MangaSekai\Database\Map;

use MangaSekai\Database\SeriesTracker;
use MangaSekai\Database\SeriesTrackerQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'series_tracker' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SeriesTrackerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'MangaSekai.Database.Map.SeriesTrackerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'series_tracker';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\MangaSekai\\Database\\SeriesTracker';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'MangaSekai.Database.SeriesTracker';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 2;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 2;

    /**
     * the column name for the id_user field
     */
    const COL_ID_USER = 'series_tracker.id_user';

    /**
     * the column name for the id_series field
     */
    const COL_ID_SERIES = 'series_tracker.id_series';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdUser', 'IdSeries', ),
        self::TYPE_CAMELNAME     => array('idUser', 'idSeries', ),
        self::TYPE_COLNAME       => array(SeriesTrackerTableMap::COL_ID_USER, SeriesTrackerTableMap::COL_ID_SERIES, ),
        self::TYPE_FIELDNAME     => array('id_user', 'id_series', ),
        self::TYPE_NUM           => array(0, 1, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdUser' => 0, 'IdSeries' => 1, ),
        self::TYPE_CAMELNAME     => array('idUser' => 0, 'idSeries' => 1, ),
        self::TYPE_COLNAME       => array(SeriesTrackerTableMap::COL_ID_USER => 0, SeriesTrackerTableMap::COL_ID_SERIES => 1, ),
        self::TYPE_FIELDNAME     => array('id_user' => 0, 'id_series' => 1, ),
        self::TYPE_NUM           => array(0, 1, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('series_tracker');
        $this->setPhpName('SeriesTracker');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\MangaSekai\\Database\\SeriesTracker');
        $this->setPackage('MangaSekai.Database');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('id_user', 'IdUser', 'INTEGER' , 'users', 'id', true, 10, null);
        $this->addForeignPrimaryKey('id_series', 'IdSeries', 'INTEGER' , 'series', 'id', true, 10, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Series', '\\MangaSekai\\Database\\Series', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id_series',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', null, false);
        $this->addRelation('Users', '\\MangaSekai\\Database\\Users', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id_user',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', null, false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \MangaSekai\Database\SeriesTracker $obj A \MangaSekai\Database\SeriesTracker object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getIdUser() || is_scalar($obj->getIdUser()) || is_callable([$obj->getIdUser(), '__toString']) ? (string) $obj->getIdUser() : $obj->getIdUser()), (null === $obj->getIdSeries() || is_scalar($obj->getIdSeries()) || is_callable([$obj->getIdSeries(), '__toString']) ? (string) $obj->getIdSeries() : $obj->getIdSeries())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \MangaSekai\Database\SeriesTracker object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \MangaSekai\Database\SeriesTracker) {
                $key = serialize([(null === $value->getIdUser() || is_scalar($value->getIdUser()) || is_callable([$value->getIdUser(), '__toString']) ? (string) $value->getIdUser() : $value->getIdUser()), (null === $value->getIdSeries() || is_scalar($value->getIdSeries()) || is_callable([$value->getIdSeries(), '__toString']) ? (string) $value->getIdSeries() : $value->getIdSeries())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \MangaSekai\Database\SeriesTracker object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)])]);
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('IdUser', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? SeriesTrackerTableMap::CLASS_DEFAULT : SeriesTrackerTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (SeriesTracker object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SeriesTrackerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SeriesTrackerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SeriesTrackerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SeriesTrackerTableMap::OM_CLASS;
            /** @var SeriesTracker $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SeriesTrackerTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SeriesTrackerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SeriesTrackerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SeriesTracker $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SeriesTrackerTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SeriesTrackerTableMap::COL_ID_USER);
            $criteria->addSelectColumn(SeriesTrackerTableMap::COL_ID_SERIES);
        } else {
            $criteria->addSelectColumn($alias . '.id_user');
            $criteria->addSelectColumn($alias . '.id_series');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SeriesTrackerTableMap::DATABASE_NAME)->getTable(SeriesTrackerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SeriesTrackerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SeriesTrackerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SeriesTrackerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SeriesTracker or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SeriesTracker object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTrackerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \MangaSekai\Database\SeriesTracker) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SeriesTrackerTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(SeriesTrackerTableMap::COL_ID_USER, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(SeriesTrackerTableMap::COL_ID_SERIES, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = SeriesTrackerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SeriesTrackerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SeriesTrackerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the series_tracker table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SeriesTrackerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SeriesTracker or Criteria object.
     *
     * @param mixed               $criteria Criteria or SeriesTracker object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTrackerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SeriesTracker object
        }


        // Set the correct dbName
        $query = SeriesTrackerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SeriesTrackerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SeriesTrackerTableMap::buildTableMap();
