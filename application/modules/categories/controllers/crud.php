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
use Bluz\Controller\Controller;

return
/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 * @return mixed
 */
function ($parentId = null) {
    /**
     * @var Controller $this
     */
    $this->assign('parentId', $parentId);

//    $crudController = new Controller\Crud();
//    $crudController->setCrud(Categories\Crud::getInstance());
//
//    return $crudController();
};
