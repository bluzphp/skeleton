<?php
/**
 * Example of REST controller for PUT/PATCH methods
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:39
 */
namespace Application;

use Application\Test;
use Bluz\Controller;

return
/**
 * @accept HTML
 * @accept JSON
 * @accept XML
 * @method PATCH
 * @method PUT
 * @return mixed
 */
function () {
    $restController = new Controller\Rest();
    $restController->setCrud(Test\Crud::getInstance());
    return $restController->methodPut();
};
