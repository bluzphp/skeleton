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

/**
 * Table
 *
 * @category Bluz
 * @package  Db
 * @example
 * <code>
 * namespace Application\Users;
 * class Table extends \Bluz\Db\Table
 * {
 *    static $instance;
 *    protected $table = 'users';
 *    protected $primary = array('id');
 * }
 *
 * $usersTable = new \Application\Users\Table();
 * $userRows = $usersTable -> find(array(1,2,3,4,5));
 * foreach ($userRows as $userRow) {
 *    $userRow -> description = 'In first 5';
 *    $userRow -> save();
 * }
 *
 * </code>
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:32
 */
abstract class Table
{
    /**
     * The schema name (default null means current schema)
     *
     * @var array
     */
    protected $schema = null;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = null;

    /**
     * Default SQL query for select
     *
     * @var string
     */
    protected $select = "";

    /**
     * The primary key column or columns.
     * A compound key should be declared as an array.
     * You may declare a single-column primary key
     * as a string.
     *
     * @var mixed
     */
    protected $primary = null;

    /**
     * @var Db
     */
    protected $adapter = null;

    /**
     * @var string
     */
    protected $rowClass = null;

    /**
     * __construct
     *
     * @throws DbException
     * @return \Bluz\Db\Table
     */
    private function __construct()
    {
        if (!$this->table) {
            throw new DbException("Table information for {".__CLASS__."} is not initialized");
        }

        if (empty($this->select)) {
            $this->select = "SELECT * FROM {$this->table}";
        }

        $this->init();
    }

    /**
     * Init
     */
    public function init()
    {
    }

    /**
     * getInstance
     *
     * @return Table
     */
    static public function getInstance()
    {
        static $instance;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Sets a DB adapter.
     *
     * @param Db $adapter DB adapter for table to use
     *
     * @return Table
     *
     * @throws DbException if default DB adapter not initiated
     *                     on \Bluz\Db::$adapter.
     */
    public function setAdapter($adapter = null)
    {
        if (null == $adapter) {
            $this->adapter = Db::getDefaultAdapter();
        }
        return $this;
    }

    /**
     * Gets a DB adapter.
     *
     * @return Db
     */
    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->setAdapter();
        }
        return $this->adapter;
    }

    /**
     * getPrimaryKey
     *
     * @return array
     *
     * @throws InvalidPrimaryKeyException if primary key was not set or has
     *                                    wrong format
     */
    public function getPrimaryKey()
    {
        if (!is_array($this->primary)) {
            throw new InvalidPrimaryKeyException("The primary key must be set as an array");
        }
        return $this->primary;
    }

    /**
     * getRowClass
     *
     * @return string
     */
    public function getRowClass()
    {
        if (!$this->rowClass) {
            $tableClass = get_called_class();
            $this->rowClass = substr($tableClass, 0, strrpos($tableClass, '\\', 1)+1) . 'Row';
        }
        return $this->rowClass;
    }

    /**
     * Fetches rows by primary key.  The argument specifies one or more primary
     * key value(s).  To find multiple rows by primary key, the argument must
     * be an array.
     *
     * This method accepts a variable number of arguments.  If the table has a
     * multi-column primary key, the number of arguments must be the same as
     * the number of columns in the primary key.  To find multiple rows in a
     * table with a multi-column primary key, each argument must be an array
     * with the same number of elements.
     *
     * The find() method always returns a Rowset object, even if only one row
     * was found.
     *
     *
     * @internal param mixed $key The value(s) of the primary keys.
     * @return Rowset Row(s) matching the criteria.
     * @throws InvalidPrimaryKeyException if wrong count of values passed
     */
    public function find()
    {
        $args = func_get_args();
        $keyNames = array_values((array) $this->primary);

        if (count($args) < count($keyNames)) {
            throw new InvalidPrimaryKeyException("Too few columns for the primary key");
        }

        if (count($args) > count($keyNames)) {
            throw new InvalidPrimaryKeyException("Too many columns for the primary key");
        }

        $whereList = array();
        $numberTerms = 0;
        foreach ($args as $keyPosition => $keyValues) {
            $keyValuesCount = count($keyValues);
            // Coerce the values to an array.
            // Don't simply typecast to array, because the values
            // might be Zend_Db_Expr objects.
            if (!is_array($keyValues)) {
                $keyValues = array($keyValues);
            }
            if ($numberTerms == 0) {
                $numberTerms = $keyValuesCount;
            } else if ($keyValuesCount != $numberTerms) {
                throw new InvalidPrimaryKeyException("Missing value(s) for the primary key");
            }
            $keyValues = array_values($keyValues);
            for ($i = 0; $i < $keyValuesCount; ++$i) {
                if (!isset($whereList[$i])) {
                    $whereList[$i] = array();
                }
                $whereList[$i][$keyPosition] = $keyValues[$i];
            }
        }

        $whereClause = null;
        $whereParams = array();
        if (count($whereList)) {
            $whereOrTerms = array();
            foreach ($whereList as $keyValueSets) {
                $whereAndTerms = array();
                foreach ($keyValueSets as $keyPosition => $keyValue) {
                    $whereAndTerms[] = $this->table . '.' . $keyNames[$keyPosition] . ' = ?';
                    $whereParams[] = $keyValue;
                }
                $whereOrTerms[] = '(' . implode(' AND ', $whereAndTerms) . ')';
            }
            $whereClause = '(' . implode(' OR ', $whereOrTerms) . ')';
        }

        return $this->fetch($this->select .' WHERE '. $whereClause, $whereParams);
    }

    /**
     * Find row
     *
     * @return Row
     */
    public function findRow()
    {
        return call_user_func_array(array($this, 'find'), func_get_args())->current();
    }

    /**
     * Support method for fetching rows.
     *
     * @param  string $sql  query options.
     * @param  array  $params
     * @return array An array containing the row results in FETCH_ASSOC mode.
     */
    protected function fetch($sql, $params = array())
    {
        $data = $this->getAdapter()->fetchObjects($sql, $params, $this->getRowClass());
        return new Rowset(array('table' => $this, 'data' => $data));
    }

    /**
     * Insert new rows.
     *
     * @param  array        $data  Column-value pairs.
     * @return int          The number of rows updated.
     */
    public function insert(array $data)
    {
        $table = ($this->schema ? $this->schema . '.' : '') . $this->table;
        return $this->getAdapter()->insert($table, $data);
    }

    /**
     * Updates existing rows.
     *
     * @param  array        $data  Column-value pairs.
     * @param  array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     * @return int          The number of rows updated.
     */
    public function update(array $data, $where)
    {
        $table = ($this->schema ? $this->schema . '.' : '') . $this->table;
        return $this->getAdapter()->update($table, $data, $where);
    }


    /**
     * Deletes existing rows.
     *
     * @param  array|string $where SQL WHERE clause(s).
     * @return int          The number of rows deleted.
     */
    public function delete($where)
    {
        $tableSpec = ($this->schema ? $this->schema . '.' : '') . $this->table;
        return $this->getAdapter()->delete($tableSpec, $where);
    }
}
