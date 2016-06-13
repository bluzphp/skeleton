<?php
/**
 * User registration
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  09.11.12 13:19
 */
namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Crud;
use Bluz\Proxy\Request;

/**
 * @accept JSON
 * @accept HTML
 *
 * @return array
 */
return function () {
    /**
     * @var Controller $this
     */
    // change layout
    if (!Request::isXmlHttpRequest()) {
        $this->useLayout('small.phtml');
    }

    $crud = new Crud();

    $crud->setCrud(Users\Crud::getInstance());

    $crud->addMap('GET', 'system', 'crud/get');
    $crud->addMap('POST', 'system', 'crud/post');

    return $crud->run();
};
