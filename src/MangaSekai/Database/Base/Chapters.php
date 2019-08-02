<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\ChapterTracker as ChildChapterTracker;
use MangaSekai\Database\ChapterTrackerQuery as ChildChapterTrackerQuery;
use MangaSekai\Database\Chapters as ChildChapters;
use MangaSekai\Database\ChaptersQuery as ChildChaptersQuery;
use MangaSekai\Database\Series as ChildSeries;
use MangaSekai\Database\SeriesQuery as ChildSeriesQuery;
use MangaSekai\Database\Map\ChapterTrackerTableMap;
use MangaSekai\Database\Map\ChaptersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'chapters' table.
 *
 *
 *
 * @package    propel.generator.MangaSekai.Database.Base
 */
abstract class Chapters implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\MangaSekai\\Database\\Map\\ChaptersTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the id_series field.
     *
     * @var        int
     */
    protected $id_series;

    /**
     * The value for the pages_count field.
     *
     * @var        int
     */
    protected $pages_count;

    /**
     * The value for the number field.
     *
     * @var        double
     */
    protected $number;

    /**
     * @var        ChildSeries
     */
    protected $aSeries;

    /**
     * @var        ObjectCollection|ChildChapterTracker[] Collection to store aggregation of ChildChapterTracker objects.
     */
    protected $collChapterTrackers;
    protected $collChapterTrackersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildChapterTracker[]
     */
    protected $chapterTrackersScheduledForDeletion = null;

    /**
     * Initializes internal state of MangaSekai\Database\Base\Chapters object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Chapters</code> instance.  If
     * <code>obj</code> is an instance of <code>Chapters</code>, delegates to
     * <code>equals(Chapters)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Chapters The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [id_series] column value.
     *
     * @return int
     */
    public function getIdSeries()
    {
        return $this->id_series;
    }

    /**
     * Get the [pages_count] column value.
     *
     * @return int
     */
    public function getPagesCount()
    {
        return $this->pages_count;
    }

    /**
     * Get the [number] column value.
     *
     * @return double
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ChaptersTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [id_series] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     */
    public function setIdSeries($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id_series !== $v) {
            $this->id_series = $v;
            $this->modifiedColumns[ChaptersTableMap::COL_ID_SERIES] = true;
        }

        if ($this->aSeries !== null && $this->aSeries->getId() !== $v) {
            $this->aSeries = null;
        }

        return $this;
    } // setIdSeries()

    /**
     * Set the value of [pages_count] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     */
    public function setPagesCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pages_count !== $v) {
            $this->pages_count = $v;
            $this->modifiedColumns[ChaptersTableMap::COL_PAGES_COUNT] = true;
        }

        return $this;
    } // setPagesCount()

    /**
     * Set the value of [number] column.
     *
     * @param double $v new value
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     */
    public function setNumber($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->number !== $v) {
            $this->number = $v;
            $this->modifiedColumns[ChaptersTableMap::COL_NUMBER] = true;
        }

        return $this;
    } // setNumber()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ChaptersTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ChaptersTableMap::translateFieldName('IdSeries', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id_series = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ChaptersTableMap::translateFieldName('PagesCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pages_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ChaptersTableMap::translateFieldName('Number', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = ChaptersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\MangaSekai\\Database\\Chapters'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aSeries !== null && $this->id_series !== $this->aSeries->getId()) {
            $this->aSeries = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChaptersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildChaptersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSeries = null;
            $this->collChapterTrackers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Chapters::setDeleted()
     * @see Chapters::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChaptersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildChaptersQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChaptersTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ChaptersTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aSeries !== null) {
                if ($this->aSeries->isModified() || $this->aSeries->isNew()) {
                    $affectedRows += $this->aSeries->save($con);
                }
                $this->setSeries($this->aSeries);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->chapterTrackersScheduledForDeletion !== null) {
                if (!$this->chapterTrackersScheduledForDeletion->isEmpty()) {
                    \MangaSekai\Database\ChapterTrackerQuery::create()
                        ->filterByPrimaryKeys($this->chapterTrackersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->chapterTrackersScheduledForDeletion = null;
                }
            }

            if ($this->collChapterTrackers !== null) {
                foreach ($this->collChapterTrackers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ChaptersTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ChaptersTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ChaptersTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_ID_SERIES)) {
            $modifiedColumns[':p' . $index++]  = 'id_series';
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_PAGES_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'pages_count';
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'number';
        }

        $sql = sprintf(
            'INSERT INTO chapters (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'id_series':
                        $stmt->bindValue($identifier, $this->id_series, PDO::PARAM_INT);
                        break;
                    case 'pages_count':
                        $stmt->bindValue($identifier, $this->pages_count, PDO::PARAM_INT);
                        break;
                    case 'number':
                        $stmt->bindValue($identifier, $this->number, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ChaptersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getIdSeries();
                break;
            case 2:
                return $this->getPagesCount();
                break;
            case 3:
                return $this->getNumber();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Chapters'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Chapters'][$this->hashCode()] = true;
        $keys = ChaptersTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getIdSeries(),
            $keys[2] => $this->getPagesCount(),
            $keys[3] => $this->getNumber(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSeries) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'series';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'series';
                        break;
                    default:
                        $key = 'Series';
                }

                $result[$key] = $this->aSeries->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collChapterTrackers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'chapterTrackers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chapter_trackers';
                        break;
                    default:
                        $key = 'ChapterTrackers';
                }

                $result[$key] = $this->collChapterTrackers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\MangaSekai\Database\Chapters
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ChaptersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\MangaSekai\Database\Chapters
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setIdSeries($value);
                break;
            case 2:
                $this->setPagesCount($value);
                break;
            case 3:
                $this->setNumber($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ChaptersTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setIdSeries($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPagesCount($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNumber($arr[$keys[3]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\MangaSekai\Database\Chapters The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ChaptersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ChaptersTableMap::COL_ID)) {
            $criteria->add(ChaptersTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_ID_SERIES)) {
            $criteria->add(ChaptersTableMap::COL_ID_SERIES, $this->id_series);
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_PAGES_COUNT)) {
            $criteria->add(ChaptersTableMap::COL_PAGES_COUNT, $this->pages_count);
        }
        if ($this->isColumnModified(ChaptersTableMap::COL_NUMBER)) {
            $criteria->add(ChaptersTableMap::COL_NUMBER, $this->number);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildChaptersQuery::create();
        $criteria->add(ChaptersTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \MangaSekai\Database\Chapters (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setIdSeries($this->getIdSeries());
        $copyObj->setPagesCount($this->getPagesCount());
        $copyObj->setNumber($this->getNumber());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChapterTrackers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChapterTracker($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \MangaSekai\Database\Chapters Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildSeries object.
     *
     * @param  ChildSeries $v
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSeries(ChildSeries $v = null)
    {
        if ($v === null) {
            $this->setIdSeries(NULL);
        } else {
            $this->setIdSeries($v->getId());
        }

        $this->aSeries = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSeries object, it will not be re-added.
        if ($v !== null) {
            $v->addChapters($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSeries object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSeries The associated ChildSeries object.
     * @throws PropelException
     */
    public function getSeries(ConnectionInterface $con = null)
    {
        if ($this->aSeries === null && ($this->id_series != 0)) {
            $this->aSeries = ChildSeriesQuery::create()->findPk($this->id_series, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSeries->addChapterss($this);
             */
        }

        return $this->aSeries;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ChapterTracker' == $relationName) {
            $this->initChapterTrackers();
            return;
        }
    }

    /**
     * Clears out the collChapterTrackers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChapterTrackers()
     */
    public function clearChapterTrackers()
    {
        $this->collChapterTrackers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChapterTrackers collection loaded partially.
     */
    public function resetPartialChapterTrackers($v = true)
    {
        $this->collChapterTrackersPartial = $v;
    }

    /**
     * Initializes the collChapterTrackers collection.
     *
     * By default this just sets the collChapterTrackers collection to an empty array (like clearcollChapterTrackers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChapterTrackers($overrideExisting = true)
    {
        if (null !== $this->collChapterTrackers && !$overrideExisting) {
            return;
        }

        $collectionClassName = ChapterTrackerTableMap::getTableMap()->getCollectionClassName();

        $this->collChapterTrackers = new $collectionClassName;
        $this->collChapterTrackers->setModel('\MangaSekai\Database\ChapterTracker');
    }

    /**
     * Gets an array of ChildChapterTracker objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChapters is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildChapterTracker[] List of ChildChapterTracker objects
     * @throws PropelException
     */
    public function getChapterTrackers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChapterTrackersPartial && !$this->isNew();
        if (null === $this->collChapterTrackers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChapterTrackers) {
                // return empty collection
                $this->initChapterTrackers();
            } else {
                $collChapterTrackers = ChildChapterTrackerQuery::create(null, $criteria)
                    ->filterByChapters($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChapterTrackersPartial && count($collChapterTrackers)) {
                        $this->initChapterTrackers(false);

                        foreach ($collChapterTrackers as $obj) {
                            if (false == $this->collChapterTrackers->contains($obj)) {
                                $this->collChapterTrackers->append($obj);
                            }
                        }

                        $this->collChapterTrackersPartial = true;
                    }

                    return $collChapterTrackers;
                }

                if ($partial && $this->collChapterTrackers) {
                    foreach ($this->collChapterTrackers as $obj) {
                        if ($obj->isNew()) {
                            $collChapterTrackers[] = $obj;
                        }
                    }
                }

                $this->collChapterTrackers = $collChapterTrackers;
                $this->collChapterTrackersPartial = false;
            }
        }

        return $this->collChapterTrackers;
    }

    /**
     * Sets a collection of ChildChapterTracker objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $chapterTrackers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildChapters The current object (for fluent API support)
     */
    public function setChapterTrackers(Collection $chapterTrackers, ConnectionInterface $con = null)
    {
        /** @var ChildChapterTracker[] $chapterTrackersToDelete */
        $chapterTrackersToDelete = $this->getChapterTrackers(new Criteria(), $con)->diff($chapterTrackers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->chapterTrackersScheduledForDeletion = clone $chapterTrackersToDelete;

        foreach ($chapterTrackersToDelete as $chapterTrackerRemoved) {
            $chapterTrackerRemoved->setChapters(null);
        }

        $this->collChapterTrackers = null;
        foreach ($chapterTrackers as $chapterTracker) {
            $this->addChapterTracker($chapterTracker);
        }

        $this->collChapterTrackers = $chapterTrackers;
        $this->collChapterTrackersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChapterTracker objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChapterTracker objects.
     * @throws PropelException
     */
    public function countChapterTrackers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChapterTrackersPartial && !$this->isNew();
        if (null === $this->collChapterTrackers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChapterTrackers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChapterTrackers());
            }

            $query = ChildChapterTrackerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChapters($this)
                ->count($con);
        }

        return count($this->collChapterTrackers);
    }

    /**
     * Method called to associate a ChildChapterTracker object to this object
     * through the ChildChapterTracker foreign key attribute.
     *
     * @param  ChildChapterTracker $l ChildChapterTracker
     * @return $this|\MangaSekai\Database\Chapters The current object (for fluent API support)
     */
    public function addChapterTracker(ChildChapterTracker $l)
    {
        if ($this->collChapterTrackers === null) {
            $this->initChapterTrackers();
            $this->collChapterTrackersPartial = true;
        }

        if (!$this->collChapterTrackers->contains($l)) {
            $this->doAddChapterTracker($l);

            if ($this->chapterTrackersScheduledForDeletion and $this->chapterTrackersScheduledForDeletion->contains($l)) {
                $this->chapterTrackersScheduledForDeletion->remove($this->chapterTrackersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildChapterTracker $chapterTracker The ChildChapterTracker object to add.
     */
    protected function doAddChapterTracker(ChildChapterTracker $chapterTracker)
    {
        $this->collChapterTrackers[]= $chapterTracker;
        $chapterTracker->setChapters($this);
    }

    /**
     * @param  ChildChapterTracker $chapterTracker The ChildChapterTracker object to remove.
     * @return $this|ChildChapters The current object (for fluent API support)
     */
    public function removeChapterTracker(ChildChapterTracker $chapterTracker)
    {
        if ($this->getChapterTrackers()->contains($chapterTracker)) {
            $pos = $this->collChapterTrackers->search($chapterTracker);
            $this->collChapterTrackers->remove($pos);
            if (null === $this->chapterTrackersScheduledForDeletion) {
                $this->chapterTrackersScheduledForDeletion = clone $this->collChapterTrackers;
                $this->chapterTrackersScheduledForDeletion->clear();
            }
            $this->chapterTrackersScheduledForDeletion[]= clone $chapterTracker;
            $chapterTracker->setChapters(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Chapters is new, it will return
     * an empty collection; or if this Chapters has previously
     * been saved, it will retrieve related ChapterTrackers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Chapters.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildChapterTracker[] List of ChildChapterTracker objects
     */
    public function getChapterTrackersJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChapterTrackerQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getChapterTrackers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aSeries) {
            $this->aSeries->removeChapters($this);
        }
        $this->id = null;
        $this->id_series = null;
        $this->pages_count = null;
        $this->number = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collChapterTrackers) {
                foreach ($this->collChapterTrackers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collChapterTrackers = null;
        $this->aSeries = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ChaptersTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
