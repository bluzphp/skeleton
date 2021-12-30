<?php

/**
 * CRUD controller for Events model
 *
 * @author   dev
 * @created  2021-12-13 20:43:01
 */

/**
 * @namespace
 */

namespace Application;

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
    $crud = new Crud(Events\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('system', 'crud/post');
    $crud->put('system', 'crud/put');
    $crud->delete('system', 'crud/delete');

    return $crud->run();
};
