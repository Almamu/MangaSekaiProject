<?php

namespace MangaSekai\Database\Base;

use \Exception;
use \PDO;
use MangaSekai\Database\Chapters as ChildChapters;
use MangaSekai\Database\ChaptersQuery as ChildChaptersQuery;
use MangaSekai\Database\Series as ChildSeries;
use MangaSekai\Database\SeriesQuery as ChildSeriesQuery;
use MangaSekai\Database\SeriesTracker as ChildSeriesTracker;
use MangaSekai\Database\SeriesTrackerQuery as ChildSeriesTrackerQuery;
use MangaSekai\Database\Map\ChaptersTableMap;
use MangaSekai\Database\Map\SeriesTableMap;
use MangaSekai\Database\Map\SeriesTrackerTableMap;
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
 * Base class that represents a row from the 'series' table.
 *
 *
 *
 * @package    propel.generator.MangaSekai.Database.Base
 */
abstract class Series implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\MangaSekai\\Database\\Map\\SeriesTableMap';


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
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the chapter_count field.
     *
     * @var        int
     */
    protected $chapter_count;

    /**
     * The value for the pages_count field.
     *
     * @var        int
     */
    protected $pages_count;

    /**
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the synced field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $synced;

    /**
     * The value for the image field.
     *
     * @var        string
     */
    protected $image;

    /**
     * The value for the path field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $path;

    /**
     * @var        ObjectCollection|ChildChapters[] Collection to store aggregation of ChildChapters objects.
     */
    protected $collChapterss;
    protected $collChapterssPartial;

    /**
     * @var        ObjectCollection|ChildSeriesTracker[] Collection to store aggregation of ChildSeriesTracker objects.
     */
    protected $collSeriesTrackers;
    protected $collSeriesTrackersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildChapters[]
     */
    protected $chapterssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSeriesTracker[]
     */
    protected $seriesTrackersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->synced = 0;
        $this->path = '';
    }

    /**
     * Initializes internal state of MangaSekai\Database\Base\Series object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Series</code> instance.  If
     * <code>obj</code> is an instance of <code>Series</code>, delegates to
     * <code>equals(Series)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Series The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [chapter_count] column value.
     *
     * @return int
     */
    public function getChapterCount()
    {
        return $this->chapter_count;
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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [synced] column value.
     *
     * @return int
     */
    public function getSynced()
    {
        return $this->synced;
    }

    /**
     * Get the [image] column value.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get the [path] column value.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[SeriesTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[SeriesTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [chapter_count] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setChapterCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->chapter_count !== $v) {
            $this->chapter_count = $v;
            $this->modifiedColumns[SeriesTableMap::COL_CHAPTER_COUNT] = true;
        }

        return $this;
    } // setChapterCount()

    /**
     * Set the value of [pages_count] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setPagesCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pages_count !== $v) {
            $this->pages_count = $v;
            $this->modifiedColumns[SeriesTableMap::COL_PAGES_COUNT] = true;
        }

        return $this;
    } // setPagesCount()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[SeriesTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [synced] column.
     *
     * @param int $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setSynced($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->synced !== $v) {
            $this->synced = $v;
            $this->modifiedColumns[SeriesTableMap::COL_SYNCED] = true;
        }

        return $this;
    } // setSynced()

    /**
     * Set the value of [image] column.
     *
     * @param string $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image !== $v) {
            $this->image = $v;
            $this->modifiedColumns[SeriesTableMap::COL_IMAGE] = true;
        }

        return $this;
    } // setImage()

    /**
     * Set the value of [path] column.
     *
     * @param string $v new value
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function setPath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->path !== $v) {
            $this->path = $v;
            $this->modifiedColumns[SeriesTableMap::COL_PATH] = true;
        }

        return $this;
    } // setPath()

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
            if ($this->synced !== 0) {
                return false;
            }

            if ($this->path !== '') {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SeriesTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SeriesTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SeriesTableMap::translateFieldName('ChapterCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->chapter_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SeriesTableMap::translateFieldName('PagesCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pages_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SeriesTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SeriesTableMap::translateFieldName('Synced', TableMap::TYPE_PHPNAME, $indexType)];
            $this->synced = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SeriesTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SeriesTableMap::translateFieldName('Path', TableMap::TYPE_PHPNAME, $indexType)];
            $this->path = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = SeriesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\MangaSekai\\Database\\Series'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(SeriesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSeriesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collChapterss = null;

            $this->collSeriesTrackers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Series::setDeleted()
     * @see Series::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSeriesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SeriesTableMap::DATABASE_NAME);
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
                SeriesTableMap::addInstanceToPool($this);
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

            if ($this->chapterssScheduledForDeletion !== null) {
                if (!$this->chapterssScheduledForDeletion->isEmpty()) {
                    \MangaSekai\Database\ChaptersQuery::create()
                        ->filterByPrimaryKeys($this->chapterssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->chapterssScheduledForDeletion = null;
                }
            }

            if ($this->collChapterss !== null) {
                foreach ($this->collChapterss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesTrackersScheduledForDeletion !== null) {
                if (!$this->seriesTrackersScheduledForDeletion->isEmpty()) {
                    \MangaSekai\Database\SeriesTrackerQuery::create()
                        ->filterByPrimaryKeys($this->seriesTrackersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->seriesTrackersScheduledForDeletion = null;
                }
            }

            if ($this->collSeriesTrackers !== null) {
                foreach ($this->collSeriesTrackers as $referrerFK) {
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

        $this->modifiedColumns[SeriesTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeriesTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeriesTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_CHAPTER_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'chapter_count';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_PAGES_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'pages_count';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_SYNCED)) {
            $modifiedColumns[':p' . $index++]  = 'synced';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_IMAGE)) {
            $modifiedColumns[':p' . $index++]  = 'image';
        }
        if ($this->isColumnModified(SeriesTableMap::COL_PATH)) {
            $modifiedColumns[':p' . $index++]  = 'path';
        }

        $sql = sprintf(
            'INSERT INTO series (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'chapter_count':
                        $stmt->bindValue($identifier, $this->chapter_count, PDO::PARAM_INT);
                        break;
                    case 'pages_count':
                        $stmt->bindValue($identifier, $this->pages_count, PDO::PARAM_INT);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'synced':
                        $stmt->bindValue($identifier, $this->synced, PDO::PARAM_INT);
                        break;
                    case 'image':
                        $stmt->bindValue($identifier, $this->image, PDO::PARAM_STR);
                        break;
                    case 'path':
                        $stmt->bindValue($identifier, $this->path, PDO::PARAM_STR);
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
        $pos = SeriesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getChapterCount();
                break;
            case 3:
                return $this->getPagesCount();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getSynced();
                break;
            case 6:
                return $this->getImage();
                break;
            case 7:
                return $this->getPath();
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

        if (isset($alreadyDumpedObjects['Series'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Series'][$this->hashCode()] = true;
        $keys = SeriesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getChapterCount(),
            $keys[3] => $this->getPagesCount(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getSynced(),
            $keys[6] => $this->getImage(),
            $keys[7] => $this->getPath(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collChapterss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'chapterss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chapterss';
                        break;
                    default:
                        $key = 'Chapterss';
                }

                $result[$key] = $this->collChapterss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeriesTrackers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'seriesTrackers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'series_trackers';
                        break;
                    default:
                        $key = 'SeriesTrackers';
                }

                $result[$key] = $this->collSeriesTrackers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\MangaSekai\Database\Series
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SeriesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\MangaSekai\Database\Series
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setChapterCount($value);
                break;
            case 3:
                $this->setPagesCount($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setSynced($value);
                break;
            case 6:
                $this->setImage($value);
                break;
            case 7:
                $this->setPath($value);
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
        $keys = SeriesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setChapterCount($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPagesCount($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDescription($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSynced($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setImage($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPath($arr[$keys[7]]);
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
     * @return $this|\MangaSekai\Database\Series The current object, for fluid interface
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
        $criteria = new Criteria(SeriesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SeriesTableMap::COL_ID)) {
            $criteria->add(SeriesTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_NAME)) {
            $criteria->add(SeriesTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_CHAPTER_COUNT)) {
            $criteria->add(SeriesTableMap::COL_CHAPTER_COUNT, $this->chapter_count);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_PAGES_COUNT)) {
            $criteria->add(SeriesTableMap::COL_PAGES_COUNT, $this->pages_count);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_DESCRIPTION)) {
            $criteria->add(SeriesTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_SYNCED)) {
            $criteria->add(SeriesTableMap::COL_SYNCED, $this->synced);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_IMAGE)) {
            $criteria->add(SeriesTableMap::COL_IMAGE, $this->image);
        }
        if ($this->isColumnModified(SeriesTableMap::COL_PATH)) {
            $criteria->add(SeriesTableMap::COL_PATH, $this->path);
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
        $criteria = ChildSeriesQuery::create();
        $criteria->add(SeriesTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \MangaSekai\Database\Series (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setChapterCount($this->getChapterCount());
        $copyObj->setPagesCount($this->getPagesCount());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setSynced($this->getSynced());
        $copyObj->setImage($this->getImage());
        $copyObj->setPath($this->getPath());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChapterss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChapters($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeriesTrackers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeriesTracker($relObj->copy($deepCopy));
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
     * @return \MangaSekai\Database\Series Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Chapters' == $relationName) {
            $this->initChapterss();
            return;
        }
        if ('SeriesTracker' == $relationName) {
            $this->initSeriesTrackers();
            return;
        }
    }

    /**
     * Clears out the collChapterss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChapterss()
     */
    public function clearChapterss()
    {
        $this->collChapterss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChapterss collection loaded partially.
     */
    public function resetPartialChapterss($v = true)
    {
        $this->collChapterssPartial = $v;
    }

    /**
     * Initializes the collChapterss collection.
     *
     * By default this just sets the collChapterss collection to an empty array (like clearcollChapterss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChapterss($overrideExisting = true)
    {
        if (null !== $this->collChapterss && !$overrideExisting) {
            return;
        }

        $collectionClassName = ChaptersTableMap::getTableMap()->getCollectionClassName();

        $this->collChapterss = new $collectionClassName;
        $this->collChapterss->setModel('\MangaSekai\Database\Chapters');
    }

    /**
     * Gets an array of ChildChapters objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSeries is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildChapters[] List of ChildChapters objects
     * @throws PropelException
     */
    public function getChapterss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChapterssPartial && !$this->isNew();
        if (null === $this->collChapterss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChapterss) {
                // return empty collection
                $this->initChapterss();
            } else {
                $collChapterss = ChildChaptersQuery::create(null, $criteria)
                    ->filterBySeries($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChapterssPartial && count($collChapterss)) {
                        $this->initChapterss(false);

                        foreach ($collChapterss as $obj) {
                            if (false == $this->collChapterss->contains($obj)) {
                                $this->collChapterss->append($obj);
                            }
                        }

                        $this->collChapterssPartial = true;
                    }

                    return $collChapterss;
                }

                if ($partial && $this->collChapterss) {
                    foreach ($this->collChapterss as $obj) {
                        if ($obj->isNew()) {
                            $collChapterss[] = $obj;
                        }
                    }
                }

                $this->collChapterss = $collChapterss;
                $this->collChapterssPartial = false;
            }
        }

        return $this->collChapterss;
    }

    /**
     * Sets a collection of ChildChapters objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $chapterss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSeries The current object (for fluent API support)
     */
    public function setChapterss(Collection $chapterss, ConnectionInterface $con = null)
    {
        /** @var ChildChapters[] $chapterssToDelete */
        $chapterssToDelete = $this->getChapterss(new Criteria(), $con)->diff($chapterss);


        $this->chapterssScheduledForDeletion = $chapterssToDelete;

        foreach ($chapterssToDelete as $chaptersRemoved) {
            $chaptersRemoved->setSeries(null);
        }

        $this->collChapterss = null;
        foreach ($chapterss as $chapters) {
            $this->addChapters($chapters);
        }

        $this->collChapterss = $chapterss;
        $this->collChapterssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Chapters objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Chapters objects.
     * @throws PropelException
     */
    public function countChapterss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChapterssPartial && !$this->isNew();
        if (null === $this->collChapterss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChapterss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChapterss());
            }

            $query = ChildChaptersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeries($this)
                ->count($con);
        }

        return count($this->collChapterss);
    }

    /**
     * Method called to associate a ChildChapters object to this object
     * through the ChildChapters foreign key attribute.
     *
     * @param  ChildChapters $l ChildChapters
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function addChapters(ChildChapters $l)
    {
        if ($this->collChapterss === null) {
            $this->initChapterss();
            $this->collChapterssPartial = true;
        }

        if (!$this->collChapterss->contains($l)) {
            $this->doAddChapters($l);

            if ($this->chapterssScheduledForDeletion and $this->chapterssScheduledForDeletion->contains($l)) {
                $this->chapterssScheduledForDeletion->remove($this->chapterssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildChapters $chapters The ChildChapters object to add.
     */
    protected function doAddChapters(ChildChapters $chapters)
    {
        $this->collChapterss[]= $chapters;
        $chapters->setSeries($this);
    }

    /**
     * @param  ChildChapters $chapters The ChildChapters object to remove.
     * @return $this|ChildSeries The current object (for fluent API support)
     */
    public function removeChapters(ChildChapters $chapters)
    {
        if ($this->getChapterss()->contains($chapters)) {
            $pos = $this->collChapterss->search($chapters);
            $this->collChapterss->remove($pos);
            if (null === $this->chapterssScheduledForDeletion) {
                $this->chapterssScheduledForDeletion = clone $this->collChapterss;
                $this->chapterssScheduledForDeletion->clear();
            }
            $this->chapterssScheduledForDeletion[]= clone $chapters;
            $chapters->setSeries(null);
        }

        return $this;
    }

    /**
     * Clears out the collSeriesTrackers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSeriesTrackers()
     */
    public function clearSeriesTrackers()
    {
        $this->collSeriesTrackers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSeriesTrackers collection loaded partially.
     */
    public function resetPartialSeriesTrackers($v = true)
    {
        $this->collSeriesTrackersPartial = $v;
    }

    /**
     * Initializes the collSeriesTrackers collection.
     *
     * By default this just sets the collSeriesTrackers collection to an empty array (like clearcollSeriesTrackers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeriesTrackers($overrideExisting = true)
    {
        if (null !== $this->collSeriesTrackers && !$overrideExisting) {
            return;
        }

        $collectionClassName = SeriesTrackerTableMap::getTableMap()->getCollectionClassName();

        $this->collSeriesTrackers = new $collectionClassName;
        $this->collSeriesTrackers->setModel('\MangaSekai\Database\SeriesTracker');
    }

    /**
     * Gets an array of ChildSeriesTracker objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSeries is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSeriesTracker[] List of ChildSeriesTracker objects
     * @throws PropelException
     */
    public function getSeriesTrackers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSeriesTrackersPartial && !$this->isNew();
        if (null === $this->collSeriesTrackers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeriesTrackers) {
                // return empty collection
                $this->initSeriesTrackers();
            } else {
                $collSeriesTrackers = ChildSeriesTrackerQuery::create(null, $criteria)
                    ->filterBySeries($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSeriesTrackersPartial && count($collSeriesTrackers)) {
                        $this->initSeriesTrackers(false);

                        foreach ($collSeriesTrackers as $obj) {
                            if (false == $this->collSeriesTrackers->contains($obj)) {
                                $this->collSeriesTrackers->append($obj);
                            }
                        }

                        $this->collSeriesTrackersPartial = true;
                    }

                    return $collSeriesTrackers;
                }

                if ($partial && $this->collSeriesTrackers) {
                    foreach ($this->collSeriesTrackers as $obj) {
                        if ($obj->isNew()) {
                            $collSeriesTrackers[] = $obj;
                        }
                    }
                }

                $this->collSeriesTrackers = $collSeriesTrackers;
                $this->collSeriesTrackersPartial = false;
            }
        }

        return $this->collSeriesTrackers;
    }

    /**
     * Sets a collection of ChildSeriesTracker objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $seriesTrackers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSeries The current object (for fluent API support)
     */
    public function setSeriesTrackers(Collection $seriesTrackers, ConnectionInterface $con = null)
    {
        /** @var ChildSeriesTracker[] $seriesTrackersToDelete */
        $seriesTrackersToDelete = $this->getSeriesTrackers(new Criteria(), $con)->diff($seriesTrackers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->seriesTrackersScheduledForDeletion = clone $seriesTrackersToDelete;

        foreach ($seriesTrackersToDelete as $seriesTrackerRemoved) {
            $seriesTrackerRemoved->setSeries(null);
        }

        $this->collSeriesTrackers = null;
        foreach ($seriesTrackers as $seriesTracker) {
            $this->addSeriesTracker($seriesTracker);
        }

        $this->collSeriesTrackers = $seriesTrackers;
        $this->collSeriesTrackersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SeriesTracker objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SeriesTracker objects.
     * @throws PropelException
     */
    public function countSeriesTrackers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSeriesTrackersPartial && !$this->isNew();
        if (null === $this->collSeriesTrackers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeriesTrackers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSeriesTrackers());
            }

            $query = ChildSeriesTrackerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeries($this)
                ->count($con);
        }

        return count($this->collSeriesTrackers);
    }

    /**
     * Method called to associate a ChildSeriesTracker object to this object
     * through the ChildSeriesTracker foreign key attribute.
     *
     * @param  ChildSeriesTracker $l ChildSeriesTracker
     * @return $this|\MangaSekai\Database\Series The current object (for fluent API support)
     */
    public function addSeriesTracker(ChildSeriesTracker $l)
    {
        if ($this->collSeriesTrackers === null) {
            $this->initSeriesTrackers();
            $this->collSeriesTrackersPartial = true;
        }

        if (!$this->collSeriesTrackers->contains($l)) {
            $this->doAddSeriesTracker($l);

            if ($this->seriesTrackersScheduledForDeletion and $this->seriesTrackersScheduledForDeletion->contains($l)) {
                $this->seriesTrackersScheduledForDeletion->remove($this->seriesTrackersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSeriesTracker $seriesTracker The ChildSeriesTracker object to add.
     */
    protected function doAddSeriesTracker(ChildSeriesTracker $seriesTracker)
    {
        $this->collSeriesTrackers[]= $seriesTracker;
        $seriesTracker->setSeries($this);
    }

    /**
     * @param  ChildSeriesTracker $seriesTracker The ChildSeriesTracker object to remove.
     * @return $this|ChildSeries The current object (for fluent API support)
     */
    public function removeSeriesTracker(ChildSeriesTracker $seriesTracker)
    {
        if ($this->getSeriesTrackers()->contains($seriesTracker)) {
            $pos = $this->collSeriesTrackers->search($seriesTracker);
            $this->collSeriesTrackers->remove($pos);
            if (null === $this->seriesTrackersScheduledForDeletion) {
                $this->seriesTrackersScheduledForDeletion = clone $this->collSeriesTrackers;
                $this->seriesTrackersScheduledForDeletion->clear();
            }
            $this->seriesTrackersScheduledForDeletion[]= clone $seriesTracker;
            $seriesTracker->setSeries(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Series is new, it will return
     * an empty collection; or if this Series has previously
     * been saved, it will retrieve related SeriesTrackers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Series.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSeriesTracker[] List of ChildSeriesTracker objects
     */
    public function getSeriesTrackersJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSeriesTrackerQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getSeriesTrackers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->chapter_count = null;
        $this->pages_count = null;
        $this->description = null;
        $this->synced = null;
        $this->image = null;
        $this->path = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collChapterss) {
                foreach ($this->collChapterss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeriesTrackers) {
                foreach ($this->collSeriesTrackers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collChapterss = null;
        $this->collSeriesTrackers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeriesTableMap::DEFAULT_STRING_FORMAT);
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
