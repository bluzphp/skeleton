<?php
/**
 * CRUD for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Pages;
use Bluz\Controller;

return
/**
 * @privilege Management
 * @return mixed
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $crudController = new Controller\Crud();
    $crudController->setCrud(Pages\Crud::getInstance());
    return $crudController();
};
