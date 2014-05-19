<?php
/**
 * Request examples
 *
 * @author   Anton Shevchuk
 * @created  25.02.14 18:01
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Request Examples',
        ]
    );
};
