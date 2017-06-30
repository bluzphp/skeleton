<?php
/**
 * PHP Load Average
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Info
 *
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Load Average');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('System', ['system', 'index']),
            __('Load Average'),
        ]
    );

    $this->assign('load', sys_getloadavg());
};
