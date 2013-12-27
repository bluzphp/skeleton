<?php
/**
 * @author   Viacheslav Nogin
 * @created  25.11.12 09:29
 */
namespace Application;

use Application\Categories;
use Bluz\Controller;
use Application\Categories\Table;

return
/**
 * @privilege Management
 */
function ($id = null, $newbranch = null, $parentId = null) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */
    try {
        $crudController = new Controller\Crud();
        $crudController->setCrud(Categories\Crud::getInstance());

        $categoriesTable = Categories\Table::getInstance();
        $view->allCategories = $categoriesTable->getAllCategories($id);

        if ($newbranch) {
            $view->newBranch = !!$newbranch;
        }

        if ($parentId) {
            $view->parentId = $parentId;
        }
    } catch (\Exception $e) {
        $view->error = $e;
    }

    return $crudController();

};
