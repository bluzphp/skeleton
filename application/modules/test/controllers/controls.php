<?php
/**
 * Test of markup
 * 
 * @author   Anton Shevchuk
 * @created  13.10.11 12:39
 * @return closure
 */
namespace Application;
use Bluz;
return
/**
 * @return \closure
 */
function() use ($bootstrap, $view) {
    /**
     * @var \closure $bootstrap
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs([
        $view->ahref('Test', ['test', 'index']),
        'Controls',
    ]);
};