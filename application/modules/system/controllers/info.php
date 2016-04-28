<?php
/**
 * PHP Info Wrapper
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function () {
    /**
     * @var Controller $this
     */
    Layout::title('PHP Info');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('System', ['system', 'index']),
            __('PHP Info'),
        ]
    );
};
