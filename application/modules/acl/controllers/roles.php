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
use Bluz\Common\Exception\CommonException;
use Bluz\Common\Exception\ComponentException;
use Bluz\Controller\Controller;
use Bluz\Controller\ControllerException;
use Bluz\Controller\Mapper\Crud;
use Bluz\Http\Exception\ForbiddenException;
use Bluz\Http\Exception\NotAcceptableException;
use Bluz\Http\Exception\NotAllowedException;
use Bluz\Http\Exception\NotImplementedException;
use ReflectionException;

/**
 * @accept    HTML
 * @accept    JSON
 * @privilege Management
 *
 * @return Controller
 * @throws CommonException
 * @throws ComponentException
 * @throws ControllerException
 * @throws ForbiddenException
 * @throws NotAcceptableException
 * @throws NotAllowedException
 * @throws NotImplementedException
 * @throws ReflectionException
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
