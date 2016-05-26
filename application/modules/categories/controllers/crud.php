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
 * @return mixed
 */
return function ($parentId = null) {
    /**
     * @var Controller $this
     */
    $this->assign('parentId', $parentId);

    $crud = new Crud();

    $crud->setCrud(Categories\Crud::getInstance());

    $crud->addMap('GET', 'system', 'crud/get', 'Read');
    $crud->addMap('POST', 'system', 'crud/post', 'Create');
    $crud->addMap('PUT', 'system', 'crud/put', 'Update');
    $crud->addMap('DELETE', 'system', 'crud/delete', 'Delete');

    return $crud->run();
};
