<?php
/**
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::title('System Module');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('System'),
        ]
    );
};
