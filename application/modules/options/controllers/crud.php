<?php
/**
 * CRUD for options
 */
namespace Application;

use Application\Options;
use Bluz\Controller;

return
    /**
     * @privilege Management
     * @return \closure
     */
    function () {
        /**
         * @var \Application\Bootstrap $this
         */
        $crudController = new Controller\Crud();
        $crudController->setCrud(Options\Crud::getInstance());
        return $crudController();
    };
