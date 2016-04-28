<?php
/**
 * Default dashboard module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return   \Closure
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @privilege Dashboard
 *
 * @return void
 */
function () {
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
