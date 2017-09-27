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
 * @accept    HTML
 * @accept    JSON
 * @privilege Management
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $crud = new Crud(Roles\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('system', 'crud/post');
    $crud->put('system', 'crud/put');
    $crud->delete('system', 'crud/delete');

    return $crud->run();
};
