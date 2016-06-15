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
use Bluz\Proxy\Messages;

/**
 * Flush cache servers
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
        Cache::flush();
        Messages::addSuccess("Cache is flushed");
    } else {
        Messages::addNotice("Cache is disabled");
    }
};
