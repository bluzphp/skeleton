<?php
/**
 * Example of DB usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
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
     * @var \closure $bootstrap
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Basic DB operations',
        ]
    );
    // all examples inside view
};
