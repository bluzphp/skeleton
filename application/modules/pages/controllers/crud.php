<?php
/**
 * CRUD for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Pages;
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

    $crud->setCrud(Pages\Crud::getInstance());

    $crud->addMap('GET', 'system', 'crud/get');
    $crud->addMap('POST', 'system', 'crud/post');
    $crud->addMap('PUT', 'system', 'crud/put');
    $crud->addMap('DELETE', 'system', 'crud/delete');

    return $crud->run();
};
