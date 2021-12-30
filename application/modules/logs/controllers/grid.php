<?php

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Management
 *
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Logs');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Logs'),
        ]
    );
    $grid = new Logs\Grid();
    $this->assign('grid', $grid);
};
