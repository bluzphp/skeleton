<?php
/**
 * Dispatch other controllers
 *
 * @author   Anton Shevchuk
 * @created  23.08.12 13:14
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @return void
 */
function () {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Dispatch',
        ]
    );
};
