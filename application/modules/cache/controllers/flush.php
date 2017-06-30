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

use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Messages;

/**
 * Clear all pool
 *
 * @accept    JSON
 * @privilege Management
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    if (Cache::getInstance()) {
        Cache::clear();
        Messages::addSuccess('Cache cleared');
    } else {
        Messages::addNotice('Cache is disabled');
    }
};
