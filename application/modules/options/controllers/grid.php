<?php
/**
 * Grid of Options
 */
namespace Application;

return
/**
 * @privilege Management
 * @return \closure
 */
function() use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        __('Options')
    ]);
    $grid = new Options\Grid();
    $view->grid = $grid;
};