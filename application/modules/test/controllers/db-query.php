<?php
/**
 * Example of DB Query builder usage
 *
 * @author   Anton Shevchuk
 * @created  07.06.13 18:28
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @return void
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
            'DB Query Builders',
        ]
    );
    // all examples inside view
};
