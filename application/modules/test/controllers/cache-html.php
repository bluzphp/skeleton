<?php
/**
 * Test Cache
 *
 * @author   Anton Shevchuk
 * @created  08.06.12 12:21
 * @return closure
 */
namespace Application;

return
/**
 * @cache-html 2
 * @param int $a
 * @var \Bluz\View\View $view
 * @return \closure
 */
function ($a = 0) use ($view) {
    $view->current = 'Time is '. date('H:i:s') . ' and $a = `'. $a .'`';
};
