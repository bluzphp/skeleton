<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Bluz;
use Application\Pages;

return
/**
 * @privilege Management
 * @return \closure
 */
function() use ($view, $module, $controller) {

    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        'Pages'
    ]);

    $grid = new Pages\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->grid = $grid;
};
