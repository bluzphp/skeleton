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
 * @param integer $a
 * @return \closure
 */
function($a = 0) use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $view->current = date('y-m-d H:i:s') . ' # '. $a;
};