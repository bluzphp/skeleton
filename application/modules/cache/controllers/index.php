<?php
/**
 * Build list of routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;

/**
 * List of cache servers
 *
 * @privilege Management
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Cache')
        ]
    );

    if ($cacheAdapter = Cache::getInstance()) {
        $this->assign('adapter', get_class($cacheAdapter));
    } else {
        $this->assign('adapter', $cacheAdapter);
        Messages::addNotice('Cache is disabled');
    }
};
