<?php
namespace Application;

use Bluz\Controller\Controller;

return
    /**
     * @param $formName
     */
    function ($formName) {
        /**
         * @var Controller $this
         */ 
        $this->assign('formName', $formName);
    };
