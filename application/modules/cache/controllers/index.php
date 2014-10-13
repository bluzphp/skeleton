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
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Cache')
        ]
    );

    if (!Cache::getInstance() instanceof Nil) {
        $view->adapter = get_class(Cache::getInstance()->getAdapter());
    } else {
        $view->adapter = null;
        Messages::addNotice("Cache is disabled");
    }
};
