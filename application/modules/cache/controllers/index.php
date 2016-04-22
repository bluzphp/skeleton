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
 * List of cache servers
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
            __('Cache')
        ]
    );

    if (!Cache::getInstance() instanceof Nil) {
        $data->adapter = get_class(Cache::getInstance()->getAdapter());
    } else {
        $data->adapter = null;
        Messages::addNotice("Cache is disabled");
    }
};
