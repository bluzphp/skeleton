<?php
/**
 * Grid of Options
 */

/**
 * @namespace
 */
namespace Application;

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
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Options')
        ]
    );
    $grid = new Options\Grid();
    $view->grid = $grid;
};
