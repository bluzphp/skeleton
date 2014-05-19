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
 * @privilege Management
 * @return mixed
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $crudController = new Controller\Crud();
    $crudController->setCrud(Options\Crud::getInstance());
    return $crudController();
};
