<?php
/**
 * Clean personal cache
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
use Bluz\Proxy\Messages;

return
/**
 * Clean data
 *
 * @privilege Management
 * @return void
 */
function () {
    /**
     * @var Bootstrap $this
     */
    if (!Cache::getInstance() instanceof Nil) {
        // routers
        Cache::delete('router:routers');
        Cache::delete('router:reverse');
        // roles
        Cache::deleteByTag('roles');
        Cache::deleteByTag('privileges');
        // reflection data
        Cache::deleteByTag('reflection');
        // db metadata
        Cache::deleteByTag('db');

        Messages::addSuccess("Cache is cleaned");
    } else {
        Messages::addNotice("Cache is disabled");
    }
};
