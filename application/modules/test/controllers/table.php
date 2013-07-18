<?php
/**
 * Example of DB\Table usage
 *
 * @author   Anton Shevchuk
 * @created  18.07.13 13:35
 */
namespace Application;

use Application\Test;

return
/**
 * @TODO: need more informative example
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     */
    $table = Test\Table::getInstance();

    debug($table->saveTestRow());
    debug($table->saveTestRow());

    debug($table->updateTestRows());
    debug($table->updateTestRows());

    debug($table->deleteTestRows());
    debug($table->deleteTestRows());

    return false;
};
 
