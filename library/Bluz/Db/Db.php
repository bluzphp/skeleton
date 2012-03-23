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

/**
 * @namespace
 */
namespace Bluz\Db;

use Bluz\Options;
use Bluz\Package;
use Bluz\Profiler;

/**
 * PDO wrapper
 *
 * @category Bluz
 * @package  Db
 *
 * <code>
 * // init settings, not connect!
 * $db->setConnect(array(
 *     'type' => 'mysql',
 *     'host' => 'localhost',
 *     'name' => 'db name',
 *     'user' => 'root',
 *     'pass' => ''
 * ));
 *
 * // quote variable
 * $db->quote($id)
 *
 * // query
 * $db->query("SET NAMES 'utf8'");
 *
 * // get one
 * $db->fetchOne("SELECT COUNT(*) FROM users");
 *
 * // get row
 * $db->fetchRow("SELECT * FROM users WHERE id = ". $db->quote($id));
 * $db->fetchRow("SELECT * FROM users WHERE id = ?", array($id));
 * $db->fetchRow("SELECT * FROM users WHERE id = :id", array(':id'=>$id));
 *
 * // get array
 * $db->fetchAll("SELECT * FROM users WHERE ip = ?", array($ip));
 * // cache for 5 minutes
 * $db->fetchAll("SELECT * FROM users WHERE ip = ?", array($ip), 5);
 *
 *
 * // get pairs
 * $pairs = $db->fetchPairs("SELECT ip, COUNT(id) FROM users GROUP BY ip", array());
 *
 * // get object
 * // .. to stdClass
 * $stdClass = $db->fetchObject('SELECT * FROM some_table WHERE id = ?', array($id));
 * // .. to new Some object
 * $someClass = $db->fetchObject('SELECT * FROM some_table WHERE id = ?', array($id), 'Some');
 * // .. to exists instance of Some object
 * $someClass = $db->fetchObject('SELECT * FROM some_table WHERE id = ?', array($id), $someClass);
 * </code>
 *
 * @author   Anton Shevchuk
 * @created  07.07.11 15:36
 */
class Db extends Package
{
    /**
     * @var array
     */
    protected $_connect = array(
        "type" => "mysql",
        "host" => "localhost",
        "name" => "",
        "user" => "root",
        "pass" => "",
    );

    /**
     * @var PDO
     */
    protected $_dbh;

    /**
     * @var array
     */
    protected $_queries;

    /**
     * Himself instance
     * @var Db
     */
    static $adapter;

    /**
     * Constructor of View
     *
     * @param array $options
     * @access  public
     */
    public function __construct($options = null)
    {
        Options::setConstructorOptions($this, $options);

        if (isset($options['defaultAdapter'])) {
            self::$adapter = $this;
        }
    }


    /**
     * setConnect
     *
     * @param array $connect options
     * @return Db
     */
    public function setConnect($connect)
    {
        $this->_connect = array_merge($this->_connect, $connect);

        if (empty($this->_connect['type']) or
            empty($this->_connect['host']) or
            empty($this->_connect['name']) or
            empty($this->_connect['user'])
            ) {
            throw new Exception('Db connection can\'t be initialized: required type, host, db name and user');
        }
        return $this;
    }

    /**
     * connect to Db
     *
     * @return Db
     */
    public function connect()
    {
        if (empty($this->_dbh)) {
            if (empty($this->_connect['type']) or
                empty($this->_connect['host']) or
                empty($this->_connect['name']) or
                empty($this->_connect['user'])
                ) {
                throw new Exception('Db connection can\'t be initialized: required type, host, db name and user');
            }
            try {
                $this->log("Connect to ".$this->_connect['host']);
                $this->_dbh = new \PDO(
                    $this->_connect['type'] .":host=". $this->_connect['host'] .";dbname=". $this->_connect['name'],
                    $this->_connect['user'],
                    $this->_connect['pass']
                );
                $this->log("Connect to ".$this->_connect['host']);
            } catch (\Exception $e) {
                throw new Exception('Attempt connection to database is failed');
            }
        }
        return $this;
    }

    /**
     * getAdapter
     *
     * @return Db
     */
    public function getAdapter()
    {
        if (!self::$adapter) {
            throw new Exception('Default Db adapter not found');
        }
        return self::$adapter;
    }

    /**
     * Return PDO handler
     *
     * @return \PDO
     */
    public function handler()
    {
        if (empty($this->_dbh)) {
            $this->connect();
        }
        return $this->_dbh;
    }

    /**
     * prepare SQL query
     *
     * @param string $sql
     * @return \PDOStatement
     */
    public function prepare($sql)
    {
        $this->log($sql);
        return $this->handler()->prepare($sql);
    }

    /**
     * quote SQL query
     *
     * @param string $value
     * @return string
     */
     public function quote($value)
     {
         return $this->handler()->quote($value);
     }

    /**
     * @param string $sql <p>
     *  "UPDATE users SET name = :name WHERE id = :id"
     *  </p>
     * @param array $params <p>
     *  array (':name' => 'John', ':id' => '123')
     * </p>
     * @return string
     */
    public function query($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $result = $stmt->execute($params);
        $this->log($sql);
        return $result;
    }

    /**
     * @param string $table
     * @param array $params <p>
     *  array (':name' => 'John', ':id' => '123')
     * </p>
     * @return string
     */
    public function insert($table, $params = array())
    {
        $sql = "INSERT INTO `$table` SET `". join('` = ?,`', array_keys($params)) ."` = ?";

        $stmt = $this->prepare($sql);

        $result = $stmt->execute(array_values($params));

        $this->log($sql);

        if ($result) {
            return $this->handler()->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * @param string $table
     * @param array $params <p>
     *  array (':name' => 'John', ':id' => '123')
     * </p>
     * @param string $where <p>
     *  "WHERE id = 123"
     * </p>
     * @return string
     */
    public function update($table, $params = array(), $where)
    {
        $sql = "UPDATE `$table` SET `". join('` = ?,`', array_keys($params)) ."` = ? " . $where ;

        $stmt = $this->prepare($sql);

        $result = $stmt->execute(array_values($params));

        $this->log($sql);

        return $result;
    }

    /**
     * @param string $table
     * @param string $where <p>
     *  "WHERE id = 123"
     * </p>
     * @return string
     */
    public function delete($table, $where)
    {
        $params = array();
        if (is_array($where)) {
            $params = array_values($where);
            $where = 'WHERE `' . join('` = ? AND `', array_keys($where)) ."` = ? ";
        }
        $sql = "DELETE FROM `$table` " . $where ;

        $stmt = $this->prepare($sql);

        $result = $stmt->execute($params);

        $this->log($sql);

        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT id FROM users WHERE name = :name AND pass = :pass"
     *  </p>
     * @param array $params <p>
     *  array (':name' => 'John', ':pass' => '123456')
     * </p>
     * @return string
     */
    public function fetchOne($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_COLUMN);

         $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT * FROM users WHERE name = :name AND pass = :pass"
     *  </p>
     * @param array $params <p>
     *  array (':name' => 'John', ':pass' => '123456')
     * </p>
     * @return array
     */
    public function fetchRow($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT * FROM users WHERE ip = :ip"
     *  </p>
     * @param array $params <p>
     *  array (':ip' => '127.0.0.1')
     * </p>
     * @return array
     */
    public function fetchAll($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT id FROM users WHERE ip = :ip"
     *  </p>
     * @param array $params <p>
     *  array (':ip' => '127.0.0.1')
     * </p>
     * @return array
     */
    public function fetchColumn($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT ip, id FROM users"
     *  </p>
     * @param array $params
     * @return array
     */
    public function fetchGroup($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_GROUP);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT ip, id FROM users"
     *  </p>
     * @param array $params
     * @return array
     */
    public function fetchColumnGroup($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_COLUMN|\PDO::FETCH_GROUP);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT id, username FROM users WHERE ip = :ip"
     *  </p>
     * @param array $params <p>
     *  array (':ip' => '127.0.0.1')
     * </p>
     * @return array
     */
    public function fetchPairs($sql, $params = array())
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT * FROM users WHERE name = :name AND pass = :pass"
     *  </p>
     * @param array $params <p>
     *  array (':name' => 'John', ':pass' => '123456')
     * </p>
     * @param mixed $object
     *
     * @internal param int $cache ttl of cache in minutes
     * @return array
     */
    public function fetchObject($sql, $params = array(), $object = null)
    {
        $stmt = $this->prepare($sql);
        $result = null;
        if (!$object) {
            // StdClass
            $stmt->setFetchMode(\PDO::FETCH_OBJ);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            $stmt->closeCursor();
        } elseif (is_object($object)) {
            // some instance
            $stmt->setFetchMode(\PDO::FETCH_INTO, $object);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_INTO);
            $stmt->closeCursor();
        } elseif (is_string($object)) {
            // some classname
            $stmt->setFetchMode(\PDO::FETCH_CLASS, $object);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_CLASS);
            $stmt->closeCursor();
        }

        $this->log($sql);
        return $result;
    }

    /**
     * @param string $sql <p>
     *  "SELECT * FROM users WHERE name = :name AND pass = :pass"
     *  </p>
     * @param array $params <p>
     *  array (':name' => 'John', ':pass' => '123456')
     * </p>
     * @param mixed $object
     * @return array
     */
    public function fetchObjects($sql, $params = array(), $object = null)
    {
        $stmt = $this->prepare($sql);
        $result = null;
        if (!$object) {
            // StdClass
            $stmt->execute($params);
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
            $stmt->closeCursor();
        } elseif (is_object($object)) {
            // some instance
            $stmt->execute($params);
            $result = $stmt->fetchAll(\PDO::FETCH_INTO, $object);
            $stmt->closeCursor();
        } elseif (is_string($object)) {
            // some classname
            $stmt->execute($params);
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, $object);
            $stmt->closeCursor();
        }

        $this->log($sql);
        return $result;
    }

    /**
     * log
     *
     * @param string $sql
     * @return void
     */
    protected function log($sql)
    {
        if (defined('DEBUG') && DEBUG) {
            if (isset($this->_queries[$sql])) {
                $this->_queries[$sql]['timer'][] = microtime(true);
                $timers = sizeof($this->_queries[$sql]['timer']);
                if ($timers%2==0) {
                    // set query time
                    $timeSpent = $this->_queries[$sql]['timer'][$timers-1] - $this->_queries[$sql]['timer'][$timers-2];
                    Profiler::log(sprintf(' >> %f >> Query: ', $timeSpent) . substr($sql, 0, 200));
                }
            } else {
                $this->_queries[$sql] = array(
                    'timer' => array(microtime(true)),
                    'point' => array()
                );
            }
        }
    }
}
