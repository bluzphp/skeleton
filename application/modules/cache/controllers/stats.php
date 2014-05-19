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

use Bluz;

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
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('Cache', ['cache', 'index']),
            __('Statistics'),
        ]
    );

    if ($handler = $this->getCache()->getAdapter()) {
        // TODO: code for inject stats of memcached/apc/etc
        $view->adapter = $handler;
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
        $this->redirectTo('cache', 'index');
    }
};
