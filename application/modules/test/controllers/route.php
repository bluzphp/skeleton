<?php
/**
 * Route examples
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
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
            'Routers Examples',
        ]
    );
};
