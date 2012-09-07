<?php
/**
 * Example of DB usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

return
function() {
    /**
     * @var Bootstrap $this
     */
    $res = $this->getDb()->fetchAll('SELECT * FROM test WHERE name LIKE "a%"');
    var_dump($res);
    return false;
};
 
