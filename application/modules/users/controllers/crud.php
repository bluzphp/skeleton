<?php
/**
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Users;
use Bluz\Controller;

return
/**
 * @privilege Management
 * @return \closure
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $crudController = new Controller\Crud();
    $crudController->setCrud(Users\Crud::getInstance());
    return $crudController();
};
