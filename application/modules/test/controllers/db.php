<?php
/**
 * Example of DB usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

return
/**
 * @TODO: need more informative example
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     */

    $random = array_rand(range('a', 'z', rand(1,5)));
    shuffle($random);

    $res = $this->getDb()->fetchAll('SELECT * FROM test WHERE name LIKE ? LIMIT 10', ['al%']);
    debug($res);

    $res = $this->getDb()->fetchRow('SELECT * FROM test WHERE name LIKE ?', ['al%']);
    debug($res);

    $res = $this->getDb()->fetchColumn('SELECT name FROM test WHERE name LIKE ? LIMIT 10', ['al%']);
    debug($res);

    $res = $this->getDb()->fetchGroup('SELECT name, id FROM test LIMIT 10');
    debug($res);

    $res = $this->getDb()->fetchColumnGroup('SELECT name, id FROM test LIMIT 10');
    debug($res);

    $table = Users\Table::getInstance();
    debug($table->getColumns());

    return false;
};
 
