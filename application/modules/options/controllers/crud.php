<?php
/**
 * CRUD for options
 */

/**
 * @namespace
 */
namespace Application;

use Application\Options;
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
    $crudController->setCrud(Options\Crud::getInstance());
    return $crudController();
};
