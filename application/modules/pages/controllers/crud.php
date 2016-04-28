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
 * @accept HTML
 * @accept JSON
 * @privilege Management
 * @return mixed
 */
function () {
    /**
     * @var Controller $this
     */
    $crudController = new Controller\Crud();
    $crudController->setCrud(Pages\Crud::getInstance());
    return $crudController();
};
