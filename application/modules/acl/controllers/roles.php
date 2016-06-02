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

    $crud->get('system', 'crud/get', 'Read');
    $crud->post('system', 'crud/post', 'Create');
    $crud->put('system', 'crud/put', 'Update');
    $crud->delete('system', 'crud/delete', 'Delete');

    return $crud->run();
};
