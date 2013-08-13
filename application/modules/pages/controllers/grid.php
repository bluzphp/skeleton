<?php
/**
 * Grid of pages
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Application\Pages\Grid;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($view, $module, $controller) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Pages')
        ]
    );

    $grid = new Pages\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->grid = $grid;
};
