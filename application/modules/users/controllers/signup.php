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
use Bluz\Controller\Crud;
use Bluz\Proxy\Request;

return
/**
 * @accept JSON
 * @accept HTML
 * @return \closure
 */
function () {
    /**
     * @var Controller $this
     */

    // change layout
    if (!Request::isXmlHttpRequest()) {
        $this->useLayout('small.phtml');
    }

    $crudController = new Crud();
    $crudController->setCrud(Users\Crud::getInstance());
    return $crudController();
};
