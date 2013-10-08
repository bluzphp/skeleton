<?php
/**
 * Example of REST controller
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  12.08.13 17:23
 */
namespace Application;

use Application\Test;
use Bluz\Controller;

return function () {
    $restController = new Controller\Rest();
    $restController->setCrud(Test\Crud::getInstance());
    return $restController();
};
