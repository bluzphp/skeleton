<?php
/**
 * Build list of routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */
namespace Application;
use Bluz;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($view) {
    /* @var \Bluz\Application $this */
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        $view->ahref('Cache', ['cache', 'index']),
        'Statistics',
    ]);

    if ($handler = $this->getCache()->getAdapter()) {
        // TODO: code for inject stats of memcached/apc/etc
        $view->adapter = $handler;
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
        $this->redirectTo('cache', 'index');
    }

};