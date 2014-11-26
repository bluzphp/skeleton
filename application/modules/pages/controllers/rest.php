<?php
/**
 * Public REST for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Pages;
use Bluz\Controller;

return
/**
 * @accept XML
 * @accept JSON
 * @accept JSONP
 * @method GET
 * @method HEAD
 * @return mixed
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $crudController = new Controller\Rest();
    $crudController->setCrud(Pages\Crud::getInstance());
    return $crudController();
};
