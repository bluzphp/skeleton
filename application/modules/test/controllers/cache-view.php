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
 * @return \closure
 */
function() use ($view) {
    $this->getLayout()->breadCrumbs([
        $view->ahref('Test', ['test', 'index']),
        'Cache View',
    ]);
    $view->current = date('y-m-d H:i:s');
};