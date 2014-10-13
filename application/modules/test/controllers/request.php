<?php
/**
 * Request examples
 *
 * @author   Anton Shevchuk
 * @created  25.02.14 18:01
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Request Examples',
        ]
    );
};
