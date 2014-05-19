<?php
/**
 * @author   Viacheslav Nogin
 * @created  25.11.12 09:29
 */

/**
 * @namespace
 */
namespace Application;

use Application\Categories;
use Bluz\Controller;

return
/**
 * @privilege Management
 * @return mixed
 */
function ($parentId = null) use ($view) {
    /**
     * @var Bootstrap $this
     */
    $view->parentId = $parentId;

    $crudController = new Controller\Crud();
    $crudController->setCrud(Categories\Crud::getInstance());

    return $crudController();
};
