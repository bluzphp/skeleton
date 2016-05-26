<?php
/**
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */

/**
 * @namespace
 */
namespace Application;

use Application\Roles;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Crud;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $crud = new Crud();

    $crud->setCrud(Roles\Crud::getInstance());

    $crud->addMap('GET', 'system', 'crud/get', 'Read');
    $crud->addMap('POST', 'system', 'crud/post', 'Create');
    $crud->addMap('PUT', 'system', 'crud/put', 'Update');
    $crud->addMap('DELETE', 'system', 'crud/delete', 'Delete');

    return $crud->run();
};
