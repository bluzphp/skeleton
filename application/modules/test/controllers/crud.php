<?php
/**
 * Example of Crud Controller
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Test;
use Bluz\Controller;

return function () {
    $crudController = new Controller\Crud();
    $crudController->setCrud(Test\Crud::getInstance());
    return $crudController();
};
