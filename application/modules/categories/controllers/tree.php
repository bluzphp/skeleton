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
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;

/**
 * @privilege Management
 * 
 * @param integer $id
 * @return array
 */
return function ($id = null) {
    /**
     * @var Controller $this
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
        return [];
    }

    if (!$id) {
        $id = $rootTree[0]->id;
    }

    return [
        'rootTree' => $rootTree,
        'branch' => $id,
        'tree' => $categoriesTable->buildTree($id)
    ];
};
