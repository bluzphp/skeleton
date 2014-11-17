<?php
/**
 * Public REST for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Pages;

return
/**
 * @return mixed
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $crudController = new Pages\Rest();
    $crudController->setCrud(Pages\Crud::getInstance());
    return $crudController();
};
