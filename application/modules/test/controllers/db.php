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
     * @var Bootstrap $this
     */
    $res = $this->getDb()->fetchAll('SELECT * FROM test WHERE name LIKE ?', ['al%']);
    var_dump($res);
    return false;
};
 
