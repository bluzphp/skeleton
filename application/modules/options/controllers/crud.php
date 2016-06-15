<?php
/**
 * CRUD for options
 */

/**
 * @namespace
 */
namespace Application;

use Application\Options;
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

    $crud->setCrud(Options\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('options', 'crud/post');
    $crud->put('system', 'crud/put');
    $crud->delete('system', 'crud/delete');
    
    return $crud->run();
};
