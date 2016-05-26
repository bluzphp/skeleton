<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
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
