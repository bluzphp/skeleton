<?php
/**
 * Grid of Options
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
/**
 * @privilege Management
 * @return void
 */
function () {
    /**
     * @var Controller $this
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Options')
        ]
    );
    $grid = new Options\Grid();
    
    $this->assign('grid', $grid);
};
