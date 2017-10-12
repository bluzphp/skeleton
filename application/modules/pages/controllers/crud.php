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
use Bluz\Proxy\Request;

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
    if (!Request::isXmlHttpRequest()) {
        $this->useLayout('dashboard.phtml');
    }

    $crud = new Crud(Pages\Crud::getInstance());

    $crud->get('system', 'crud/get');
    $crud->post('system', 'crud/post');
    $crud->put('system', 'crud/put');
    $crud->delete('system', 'crud/delete');

    return $crud->run();
};
