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
 * List of cache servers
 * @privilege Management
 * @return \closure
 */
function () use ($view) {
    /* @var \Bluz\Application $this */
    $this->getLayout()->breadCrumbs([
        'Cache'
    ]);

    if ($handler = $this->getCache()->handler()) {
        $view->servers = $handler->getServerList();
    } else {
        $view->servers = [];
        $this->getMessages()->addNotice("Cache is disabled");
    }
};