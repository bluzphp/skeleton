<?php
namespace Application;

use Bluz\Controller\Controller;

/**
 * @param $formName
 * @return void
 */
return function ($formName) {
    /**
     * @var Controller $this
     */ 
    $this->assign('formName', $formName);
};
