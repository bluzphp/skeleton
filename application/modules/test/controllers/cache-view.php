<?php
/**
 * Test Cache
 *
 * @author   Anton Shevchuk
 * @created  08.06.12 12:21
 * @return closure
 */
namespace Application;

use Bluz;

return
/**
 * @cache 2
 * @param int $a
 * @return \closure
 */
function ($a = 0) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $view->current = 'Time is '. date('H:i:s') . ' and $a = `'. $a .'`';
};
