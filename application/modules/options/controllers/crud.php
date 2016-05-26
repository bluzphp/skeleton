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

    $crud->addMap('GET', 'system', 'crud/get');
    $crud->addMap('POST', 'system', 'crud/post');
    $crud->addMap('PUT', 'system', 'crud/put');
    $crud->addMap('DELETE', 'system', 'crud/delete');
    
    return $crud->run();
};
