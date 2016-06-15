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

use Bluz\Common\Nil;
use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;

/**
 * Statistics
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
            Layout::ahref('Cache', ['cache', 'index']),
            __('Statistics'),
        ]
    );

    if (!Cache::getInstance() instanceof Nil) {
        $this->assign('adapter', Cache::getInstance()->getAdapter());
    } else {
        Messages::addNotice("Cache is disabled");
        Response::redirectTo('cache', 'index');
    }
};
