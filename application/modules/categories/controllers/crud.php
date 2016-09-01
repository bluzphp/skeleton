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
use Bluz\Controller\Mapper\Crud;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 *
 * @param  integer $parentId
 * @param  integer $rootId
 * @return mixed
 */
return function ($parentId = null, $rootId = null) {
    /**
     * @var Controller $this
     */
    $this->assign('parentId', $parentId);
    $this->assign('rootId', $rootId);

    if ($rootId) {
        // get tree of category
        // required array for view
        $tree = array(Categories\Table::getInstance()->buildTree($rootId));
    } else {
        // get root categories
        $tree = Categories\Table::getInstance()->getRootCategories();
        $rootRow = new Categories\Row();
        $rootRow->name = '---';
        array_unshift($tree, $rootRow);
    }
    $this->assign('tree', $tree);

    $crud = new Crud();

    $crud->setCrud(Categories\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('system', 'crud/post');
    $crud->put('system', 'crud/put');
    $crud->delete('system', 'crud/delete');

    return $crud->run();
};
