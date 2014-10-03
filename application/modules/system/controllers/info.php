<?php
/**
 * PHP Info Wrapper
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
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
    Layout::title('PHP Info');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('System', ['system', 'index']),
            __('PHP Info'),
        ]
    );
};
