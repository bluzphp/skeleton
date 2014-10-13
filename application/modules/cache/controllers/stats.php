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
 * Statistics
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
            $view->ahref('Cache', ['cache', 'index']),
            __('Statistics'),
        ]
    );

    if (!Cache::getInstance() instanceof Nil) {
        $view->adapter = Cache::getInstance()->getAdapter();
    } else {
        Messages::addNotice("Cache is disabled");
        $this->redirectTo('cache', 'index');
    }
};
