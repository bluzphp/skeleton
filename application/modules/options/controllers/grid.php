<?php
/**
 * Grid of Options
 */

/**
 * @namespace
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
function () use ($data) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Options')
        ]
    );
    $grid = new Options\Grid();
    $data->grid = $grid;
};
