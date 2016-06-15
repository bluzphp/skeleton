<?php
/**
 * Example of Crud Controller
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Test;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Crud;

/**
 * @acl Read
 * @acl Create
 * @acl Update
 * @acl Delete
 *
 * @accept HTML
 * @accept JSON
 */
return function () {
    /**
     * @var Controller $this
     */
    $crud = new Crud();

    $crud->setCrud(Test\Crud::getInstance());

    $crud->get('system', 'crud/get', 'Read');
    $crud->post('system', 'crud/post', 'Create');
    $crud->put('system', 'crud/put', 'Update');
    $crud->delete('system', 'crud/delete', 'Delete');

    return $crud->run();
};
