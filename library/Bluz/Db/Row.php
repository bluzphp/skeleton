<?php
/**
 * Copyright (c) 2012 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Bluz\Db;

use Bluz\Db\DbException;

/**
 * Row
 *
 * @category Bluz
 * @package  Db
 * @example
 * <code>
 * namespace Application\Users;
 * class Row extends \Bluz\Db\Row
 * {
 *    public function preInsert()
 *    {
 *        $this->created = gmdate('Y-m-d H:i:s');
 *    }
 *
 *    public function preUpdate()
 *    {
 *        $this->updated = gmdate('Y-m-d H:i:s');
 *    }
 * }
 *
 * $userRow = new \Application\Users\Row();
 * $userRow -> login = 'username';
 * $userRow -> save();
 * </code>
 *
 * @author   Anton Shevchuk
 * @created  07.07.11 19:47
 */
class Row
{
    /**
     * Table class or instance.
     *
     * @var Table
     */
    protected $table = null;

    /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $primary;

    /**
     * The data for each column in the row (column_name => value).
     * The keys must match the physical names of columns in the
     * table for which this row is defined.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Tracks columns where data has been updated. Allows more specific insert and
     * update operations.
     *
     * @var array
     */
    protected $modified = array();

    /**
     * This is set to a copy of $data when the data is fetched from
     * a database, specified as a new tuple in the constructor, or
     * when dirty data is posted to the database with save().
     *
     * @var array
     */
    protected $clean = array();

    /**
     * Relations rows
     *
     * @var array
     */
    protected $relations = array();

    /**
     * Relations data
     *
     * @var array
     */
    protected $relationsData = array();

    /**
     * @var array
     */
    protected static $columns = array();

    /**
     * __construct
     *
     * @todo added cache for reflexion
     * @param array $data
     * @return \Bluz\Db\Row
     */
    public function __construct($data = array())
    {
        // clean modified flags data if setup from PDO
        $this->modified = array();

        // original cleaner data
        $this->clean = $this->data;

        $className = get_class($this);
        if (empty(self::$columns[$className])) {
            self::$columns[$className] = array();
            $reflection = new \ReflectionClass($this);

            if (preg_match_all('/\@property\s+(\w+)\s+\$([\w\d]+)$/im', $reflection->getDocComment(), $matches)) {
                for ($i = 0; $i < count($matches[1]); $i++) {
                    self::$columns[$className][$matches[2][$i]] = $matches[1][$i];
                }
            }
        }
        // not clean data, but not modified
        $this->setFromArray($data);
    }

    /**
     * Retrieve row field value
     *
     * @param  string $columnName The user-specified column name.
     * @return string             The corresponding column value.
     * @throws DbException if the $columnName is not a column in the row.
     */
    public function __get($columnName)
    {
        if (!$this->__isset($columnName)) {
            throw new DbException("Column '{$columnName}' is not a column in the " . get_class($this));
        }
        $value = null;
        if (isset($this->data[$columnName])) {
            $value = $this->data[$columnName];
        }

        $className = get_class($this);
        if (!empty(self::$columns[$className])) {
            $type = self::$columns[$className][$columnName];

            if ('object' == $type || 'array' == $type) {
                if (!empty($value)) {
                    $value = unserialize($value);
                }
            } else {
                setType($value, self::$columns[$className][$columnName]);
            }
        }
        return $value;
    }

    /**
     * Sleep
     * @return array
     */
    public function __sleep()
    {
        return array('primary', 'data', 'clean' ,'modified');
    }

    /**
     * Set row field value
     *
     * @param  string $columnName The column key.
     * @param  mixed  $value      The value for the property.
     * @return void
     * @throws DbException
     */
    public function __set($columnName, $value)
    {
        if (strpos($columnName, '__') === 0) {
            list($tableName, $columnName) = preg_split('/_/', substr($columnName, 2), 2);
            $tableName = ucfirst(strtolower($tableName));

            if (!empty($tableName) && !empty($columnName)) {

                if (!isset($this->relationsData[$tableName])) {
                    $this->relationsData[$tableName] = array();
                }
                $this->relationsData[$tableName][$columnName] = $value;
            }
        } else {
            if (!$this->__isset($columnName)) {
                throw new DbException("Column '{$columnName}' is not a column in the " . get_class($this));
            }
            if (is_array($value) || is_object($value)) {
                $value = serialize($value);
            }
            if (!isset($this->data[$columnName])
                || $this->data[$columnName] != $value) {
                $this->data[$columnName] = $value;
                $this->modified[$columnName] = true;
            }
        }
    }

    /**
     * Test existence of row field
     *
     * @param  string  $columnName   The column key.
     * @return boolean
     */
    public function __isset($columnName)
    {
        $className = get_class($this);
        if (!empty(self::$columns[$className])) {
            return isset(self::$columns[$className][$columnName]);
        }
        return true;
    }

    /**
     * Saves the properties to the database.
     *
     * This performs an intelligent insert/update, and reloads the
     * properties with fresh data from the table on success.
     *
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
     */
    public function save()
    {
        /**
         * If the _clean array is empty,
         * this is an INSERT of a new row.
         * Otherwise it is an UPDATE.
         */
        if (empty($this->clean)) {
            return $this->doInsert();
        } else {
            return $this->doUpdate();
        }
    }

    /**
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
     */
    protected function doInsert()
    {
        /**
         * Run pre-INSERT logic
         */
        $this->preInsert();

        /**
         * Execute the INSERT (this may throw an exception)
         */
        $data = array_intersect_key($this->data, $this->modified);
        $primaryKey = $this->getTable()->insert($data);

        /**
         * Normalize the result to an array indexed by primary key column(s).
         * The table insert() method may return a scalar.
         * TODO: check scalar!!!
         */
        if (is_array($primaryKey)) {
            $newPrimaryKey = $primaryKey;
        } else {
            //ZF-6167 Use tempPrimaryKey temporary to avoid that zend encoding fails.
            $tempPrimaryKey = $this->getTable()->getPrimaryKey();
            $newPrimaryKey = array(current($tempPrimaryKey) => $primaryKey);
        }

        /**
         * Save the new primary key value in _data.  The primary key may have
         * been generated by a sequence or auto-increment mechanism, and this
         * merge should be done before the _postInsert() method is run, so the
         * new values are available for logging, etc.
         */
        $this->data = array_merge($this->data, $newPrimaryKey);

        /**
         * Run post-INSERT logic
         */
        $this->postInsert();

        /**
         * Update the _clean to reflect that the data has been inserted.
         */
        $this->refresh();

        return $primaryKey;
    }

    /**
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
     */
    protected function doUpdate()
    {
        /**
         * Run pre-UPDATE logic
         */
        $this->preUpdate();

        /**
         * Compare the data to the modified fields array to discover
         * which columns have been changed.
         */
        $diffData = array_intersect_key($this->data, $this->modified);

        /**
         * Execute the UPDATE (this may throw an exception)
         * Do this only if data values were changed.
         * Use the $diffData variable, so the UPDATE statement
         * includes SET terms only for data values that changed.
         */
        if (count($diffData) > 0) {
            $this->getTable()->update($diffData, $this->getPrimaryKey());
        }

        /**
         * Run post-UPDATE logic.  Do this before the _refresh()
         * so the _postUpdate() function can tell the difference
         * between changed data and clean (pre-changed) data.
         */
        $this->postUpdate();

        /**
         * Refresh the data just in case triggers in the RDBMS changed
         * any columns.  Also this resets the _clean.
         */
        $this->refresh();

        /**
         * Return the primary key value(s) as an array
         * if the key is compound or a scalar if the key
         * is a scalar.
         */
        $primaryKey = $this->getPrimaryKey();
        if (count($primaryKey) == 1) {
            return current($primaryKey);
        }

        return $primaryKey;
    }

    /**
     * Deletes existing rows.
     *
     * @return int The number of rows deleted.
     */
    public function delete()
    {
        /**
         * Execute pre-DELETE logic
         */
        $this->preDelete();

        /**
         * Execute the DELETE (this may throw an exception)
         */
        $result = $this->getTable()->delete($this->getPrimaryKey());

        /**
         * Execute post-DELETE logic
         */
        $this->postDelete();

        /**
         * Reset all fields to null to indicate that the row is not there
         */
        $this->data = array_combine(
            array_keys($this->data),
            array_fill(0, count($this->data), null)
        );

        return $result;
    }


    /**
     * Retrieves an associative array of primary keys.
     *
     * @throws InvalidPrimaryKeyException
     * @return array
     */
    protected function getPrimaryKey()
    {
        $primary = array_flip($this->getTable()->getPrimaryKey());

        $array = array_intersect_key($this->data, $primary);

        if (count($primary) != count($array)) {
            throw new InvalidPrimaryKeyException(
                "The specified Table '" . get_class($this->table)
                . "' does not have the same primary key as the Row"
            );
        }
        return $array;
    }


    /**
     * Refreshes properties from the database.
     *
     * @return void
     */
    public function refresh()
    {
        $this->clean = $this->data;
        $this->modified = array();
    }

    /**
     * Pre insert hook
     * @return void
     */
    protected function preInsert()
    {
    }

    /**
     * Allows post-insert logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function postInsert()
    {
    }

    /**
     * Pre update hook
     * @return void
     */
    protected function preUpdate()
    {
    }

    /**
     * Allows post-update logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function postUpdate()
    {
    }

    /**
     * Pre delete hook
     * @return void
     */
    protected function preDelete()
    {
    }

    /**
     * Allows post-delete logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    protected function postDelete()
    {
    }

    /**
     * Returns the table object, or null if this is disconnected row
     *
     * @throws TableNotFoundException
     * @return Table|null
     */
    public function getTable()
    {
        if ($this->table instanceof Table) {
            return $this->table;
        } else {

            if (is_string($this->table)) {
                $classTable = $this->table;
            } else {
                // try to guess table class

                $classRow = get_class($this);
                /**
                 * @var string $classTable is child of \Bluz\Db\Table
                 */
                $classTable = substr($classRow, 0, strrpos($classRow, '\\', 1)+1) . 'Table';
            }

            if (class_exists($classTable)) {
                $table = call_user_func(array($classTable, 'getInstance'));

                if ($table) {
                    $this->table = $table;
                    return $this->table;
                }
            }
        }

        throw new TableNotFoundException('Can\'t found table class');
    }

    /**
     * getRelation
     *
     * @param string $tableName
     * @throws RelationNotFoundException
     * @return \Bluz\Db\Row
     */
    public function getRelation($tableName)
    {
        $tableName = ucfirst(strtolower($tableName));
        if (isset($this->relations[$tableName])) {
            return $this->relations[$tableName];
        } elseif (!isset($this->relationsData[$tableName])) {
            throw new RelationNotFoundException(
                'Can\'t found relation for "'.$tableName.'"'
            );
        }
        $currentClass = get_class($this);
        $classRow = substr($currentClass, 0, strrpos($currentClass, '\\'));
        $classRow = substr($currentClass, 0, strrpos($classRow, '\\'));
        $classRow = $classRow .'\\'.$tableName.'\\Row';

        $this->relations[$tableName] = new $classRow($this->relationsData[$tableName]);

        return $this->relations[$tableName];
    }

    /**
     * setRelation
     *
     * @param Row $row
     * @return Row
     */
    public function setRelation(Row $row)
    {
        $class = get_class($row);
        $this->relations[$class] = $row;
        return $this;
    }

    /**
     * Returns the column/value data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this->data;
    }

    /**
     * Sets all data in the row from an array.
     *
     * @param  array $data
     * @return Row Provides a fluent interface
     */
    public function setFromArray(array $data)
    {
        $className = get_class($this);
        if (!empty(self::$columns[$className])) {
            $data = array_intersect_key($data, self::$columns[$className]);
        }

        foreach ($data as $columnName => $value) {
            $this->__set($columnName, $value);
        }

        return $this;
    }

}