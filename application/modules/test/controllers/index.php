<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 * @return closure
 */
namespace Application;
use Bluz;
return
/**
 * @return \closure
 */
function() use ($bootstrap, $view) {
    $view->title('Test Module');
    $view->title('Append', $view::POS_APPEND);
    $view->title('Prepend', $view::POS_PREPEND);
    /**
     * @var \closure $bootstrap
     * @var Bootstrap $this
     * @var Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs([
        'Test',
    ]);
};