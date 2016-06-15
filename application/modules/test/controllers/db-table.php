<?php
/**
 * Example of DB\Table usage
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  18.07.13 13:35
 */
namespace Application;

use Application\Test;
use Bluz\Controller\Controller;

/**
 * @TODO: need more informative example
 * @return bool
 */
return function () {
    /**
     * @var Controller $this
     */
    $table = Test\Table::getInstance();

    debug($table->saveTestRow());
    debug($table->updateTestRows());
    debug($table->deleteTestRows());

    $table = Users\Table::getInstance();

    var_dump($table->getColumns());
    var_dump($table->findRow(['1']));
    var_dump($table->findRowWhere(['id' => '0']));

    return false;
};
