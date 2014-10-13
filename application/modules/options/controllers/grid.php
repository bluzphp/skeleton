<?php
/**
 * Grid of Options
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @privilege Management
 * @return void
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Options')
        ]
    );
    $grid = new Options\Grid();
    $view->grid = $grid;
};
