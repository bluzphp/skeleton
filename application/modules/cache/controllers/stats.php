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
use Bluz\Controller\Data;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;

return
/**
 * Statistics
 *
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
            Layout::ahref('Cache', ['cache', 'index']),
            __('Statistics'),
        ]
    );

    if (!Cache::getInstance() instanceof Nil) {
        $data->adapter = Cache::getInstance()->getAdapter();
    } else {
        Messages::addNotice("Cache is disabled");
        $this->redirectTo('cache', 'index');
    }
};
