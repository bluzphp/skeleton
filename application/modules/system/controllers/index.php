<?php
/**
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz\Proxy\Layout;

/**
 * @privilege Info
 *
 * @return \closure
 */
return function () {
    Layout::title('System Module');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('System'),
        ]
    );
};
