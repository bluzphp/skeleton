<?php
/**
 * Example of REST controller for OPTIONS method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:46
 */
namespace Application;

use Application\Test;
use Bluz\Controller;

return
/**
 * @accept HTML
 * @accept JSON
 * @method OPTIONS
 * @return mixed
 */
function () {
    $restController = new Controller\Rest();
    $restController->setCrud(Test\Crud::getInstance());
    return $restController->methodOptions();
};
