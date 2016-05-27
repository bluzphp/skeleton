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
use Bluz\Controller;
use Bluz\Proxy\Request;

return
/**
 * @accept JSON
 * @accept HTML
 * @return \closure
 */
function () {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */

    // change layout
    if (!Request::isXmlHttpRequest()) {
        $this->useLayout('small.phtml');
    }

    $crudController = new Controller\Crud();
    $crudController->setCrud(Users\Crud::getInstance());
    return $crudController();
};
