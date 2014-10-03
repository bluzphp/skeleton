<?php
/**
 * Example of backbone usage
 *
 * @category Application
 *
 * @author   dark
 * @created  13.08.13 17:16
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
            'Backbone',
        ]
    );
};
