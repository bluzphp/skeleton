<?php
/**
 * Test Cache
 *
 * @author   Anton Shevchuk
 * @created  08.06.12 12:21
 * @return closure
 */
namespace Application;

use Bluz\Controller\Controller;

return
/**
 * @param int $a
 * @return \closure
 */
function ($a = 0) {
    /**
     * @var Controller $this
     */
    $this->assign('current', 'Time is '. date('H:i:s') . ' and $a = `'. $a .'`');
};
