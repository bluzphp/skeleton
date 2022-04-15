<?php

/**
 * Grid controller for Events model
 *
 * @author   dev
 * @created  2021-12-13 20:39:33
 */

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Management
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Events');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Events'),
        ]
    );
    $grid = new Events\Grid();
    $this->assign('grid', $grid);
};
