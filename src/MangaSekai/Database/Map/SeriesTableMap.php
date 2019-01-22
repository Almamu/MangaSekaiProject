<?php

namespace MangaSekai\Database\Map;

use MangaSekai\Database\Series;
use MangaSekai\Database\SeriesQuery;
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
 * This class defines the structure of the 'series' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SeriesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'MangaSekai.Database.Map.SeriesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'series';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\MangaSekai\\Database\\Series';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'MangaSekai.Database.Series';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the id field
     */
    const COL_ID = 'series.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'series.name';

    /**
     * the column name for the chapter_count field
     */
    const COL_CHAPTER_COUNT = 'series.chapter_count';

    /**
     * the column name for the pages_count field
     */
    const COL_PAGES_COUNT = 'series.pages_count';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'series.description';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'ChapterCount', 'PagesCount', 'Description', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'chapterCount', 'pagesCount', 'description', ),
        self::TYPE_COLNAME       => array(SeriesTableMap::COL_ID, SeriesTableMap::COL_NAME, SeriesTableMap::COL_CHAPTER_COUNT, SeriesTableMap::COL_PAGES_COUNT, SeriesTableMap::COL_DESCRIPTION, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'chapter_count', 'pages_count', 'description', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'ChapterCount' => 2, 'PagesCount' => 3, 'Description' => 4, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'chapterCount' => 2, 'pagesCount' => 3, 'description' => 4, ),
        self::TYPE_COLNAME       => array(SeriesTableMap::COL_ID => 0, SeriesTableMap::COL_NAME => 1, SeriesTableMap::COL_CHAPTER_COUNT => 2, SeriesTableMap::COL_PAGES_COUNT => 3, SeriesTableMap::COL_DESCRIPTION => 4, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'chapter_count' => 2, 'pages_count' => 3, 'description' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('series');
        $this->setPhpName('Series');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\MangaSekai\\Database\\Series');
        $this->setPackage('MangaSekai.Database');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('chapter_count', 'ChapterCount', 'INTEGER', true, null, null);
        $this->addColumn('pages_count', 'PagesCount', 'INTEGER', true, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Chapters', '\\MangaSekai\\Database\\Chapters', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':id_series',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'Chapterss', false);
        $this->addRelation('SeriesTracker', '\\MangaSekai\\Database\\SeriesTracker', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':id_series',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'SeriesTrackers', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to series     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ChaptersTableMap::clearInstancePool();
        SeriesTrackerTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
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
        return $withPrefix ? SeriesTableMap::CLASS_DEFAULT : SeriesTableMap::OM_CLASS;
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
     * @return array           (Series object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SeriesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SeriesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SeriesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SeriesTableMap::OM_CLASS;
            /** @var Series $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SeriesTableMap::addInstanceToPool($obj, $key);
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
            $key = SeriesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SeriesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Series $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SeriesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SeriesTableMap::COL_ID);
            $criteria->addSelectColumn(SeriesTableMap::COL_NAME);
            $criteria->addSelectColumn(SeriesTableMap::COL_CHAPTER_COUNT);
            $criteria->addSelectColumn(SeriesTableMap::COL_PAGES_COUNT);
            $criteria->addSelectColumn(SeriesTableMap::COL_DESCRIPTION);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.chapter_count');
            $criteria->addSelectColumn($alias . '.pages_count');
            $criteria->addSelectColumn($alias . '.description');
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
        return Propel::getServiceContainer()->getDatabaseMap(SeriesTableMap::DATABASE_NAME)->getTable(SeriesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SeriesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SeriesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SeriesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Series or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Series object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \MangaSekai\Database\Series) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SeriesTableMap::DATABASE_NAME);
            $criteria->add(SeriesTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SeriesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SeriesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SeriesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the series table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SeriesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Series or Criteria object.
     *
     * @param mixed               $criteria Criteria or Series object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Series object
        }

        if ($criteria->containsKey(SeriesTableMap::COL_ID) && $criteria->keyContainsValue(SeriesTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SeriesTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SeriesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SeriesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SeriesTableMap::buildTableMap();
