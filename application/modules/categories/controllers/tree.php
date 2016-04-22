<?php
/**
 * Grid of Categories
 *
 * @author   Viacheslav Nogin
 * @created  25.11.12 18:39
 * @return   \Closure
 */

/**
 * @namespace
 */
namespace Application;

use Application\Categories;
use Bluz\Controller\Controller;
use Bluz\Controller\Data;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;

return
/**
 * @privilege Management
 */
function ($id = null) use ($data) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::headStyle(Layout::baseUrl('css/categories.css'));
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'grid']),
            __('Categories')
        ]
    );

    $categoriesTable = Categories\Table::getInstance();
    $rootTree = $categoriesTable->getAllRootCategory();

    if (count($rootTree) == 0) {
        Messages::addNotice('There are no categories');
        return;
    }

    $data->rootTree = $rootTree;
    if (!$id) {
        $id = $rootTree[0]->id;
    }

    $data->branch = $id;
    $data->tree = $categoriesTable->buildTree($id);
};
