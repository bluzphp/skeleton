<?php
/**
 * Example of Crud
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Bluz;
use Application\Test;

return
/**
 * @return \closure
 */
function() use ($view) {
    $crud = new Test\Crud();
    $crud->processRequest();

var_dump($crud->getResult());
};
