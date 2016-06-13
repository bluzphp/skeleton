<?php
/**
 * Test Cache
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  08.06.12 12:21
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @cache 2
 *
 * @param int $a
 * @return void
 */
return function ($a = 0) {
    /**
     * @var Controller $this
     */
    $this->assign('current', 'Time is '. date('H:i:s') . ' and $a = `'. $a .'`');
};
