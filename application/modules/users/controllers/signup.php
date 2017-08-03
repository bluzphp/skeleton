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
 */
return function () {
    /**
     * @var Controller $this
     */
    // change layout
    if (!Request::isXmlHttpRequest()) {
        $this->useLayout('small.phtml');
    }

    $crud = new Crud(Users\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('users', 'crud/post');

    return $crud->run();
};
