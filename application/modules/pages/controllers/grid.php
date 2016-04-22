<?php
/**
 * Grid of pages
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Controller\Data;
use Bluz\Proxy\Layout;

return
/**
 * @privilege Management
 * @return void
 */
function () use ($data, $module, $controller) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Pages')
        ]
    );

    $grid = new Pages\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $data->grid = $grid;
};
