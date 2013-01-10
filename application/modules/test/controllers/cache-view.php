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
 * @cache 1 minute
 * @param integer $a
 * @return \closure
 */
function($a = 0) use ($view) {
    $view->current = date('y-m-d H:i:s') . ' # '. $a;
};