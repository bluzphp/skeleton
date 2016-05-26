<?php
/**
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Crud;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    $crud = new Crud();

    $crud->setCrud(Users\Crud::getInstance());

    $crud->addMap('GET', 'system', 'crud/get', 'Read');
    $crud->addMap('POST', 'system', 'crud/post', 'Create');
    $crud->addMap('PUT', 'system', 'crud/put', 'Update');
    $crud->addMap('DELETE', 'system', 'crud/delete', 'Delete');

    return $crud->run();
};
