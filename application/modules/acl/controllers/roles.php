<?php
/**
 * Example of Crud
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Roles;
use Bluz\Controller;

return
/**
 * @privilege Management
 * @return \closure
 */
function () {
    /**
     * @var \Application\Bootstrap $this
     */
    $crudController = new Controller\Crud();
    $crudController->setCrud(Roles\Crud::getInstance());
    return $crudController();
};
