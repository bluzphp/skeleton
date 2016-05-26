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
use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Messages;

/**
 * Clean data
 *
 * @accept JSON
 * @privilege Management
 * @return void
 */
return function () {
    /**
     * @var Controller $this
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
        // view data
        Cache::deleteByTag('view');
        // html data
        Cache::deleteByTag('html');

        Messages::addSuccess("Cache is cleaned");
    } else {
        Messages::addNotice("Cache is disabled");
    }
};
