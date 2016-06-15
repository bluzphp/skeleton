<?php
/**
 * Test AJAX
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->assign('time', date('H:i:s'));
};
