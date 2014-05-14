<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($bootstrap, $view) {
    /**
     * @var Bootstrap $this
     * @var \closure $bootstrap
     * @var Bluz\View\View $view
     */
    $view->title('Test Module');
    $view->title('Append', $view::POS_APPEND);
    $view->title('Prepend', $view::POS_PREPEND);
    $this->getLayout()->breadCrumbs(['Test']);
};
