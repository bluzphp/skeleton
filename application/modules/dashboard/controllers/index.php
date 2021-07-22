<?php

/**
 * Default dashboard module/controller
 *
 * @return   \Closure
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 */

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Dashboard
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            __('Dashboard'),
        ]
    );
    Layout::setTemplate('dashboard.phtml');
};
