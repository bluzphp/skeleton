<?php
/**
 * Grid of Categories
 *
 * @author   Viacheslav Nogin
 * @created  25.11.12 18:39
 * @return closure
 */
namespace Application;

use Bluz;
use Application\Categories;

return
/**
 * @privilege Management
 */
function ($id = null) use ($view) {

    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->headStyle($view->baseUrl('css/categories.css'));
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'grid']),
            __('Categories')
        ]
    );

    try {
        $categoriesTable = Categories\Table::getInstance();
        $rootTree = $categoriesTable->getAllRootCategory();

        if (count($rootTree) == 0) {
            throw new Exception('There are no categories');
        }

        $view->rootTree = $rootTree;
        if (!$id) {
            $id = $rootTree[0]->id;
        }

        $view->branch = $id;
        $view->tree = $categoriesTable->buildTree($id);
    } catch (\Exception $e) {
        $view->error = $e->getMessage();
    }
};
