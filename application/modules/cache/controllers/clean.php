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

use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Messages;

/**
 * Clean system data
 *
 * @accept JSON
 * @privilege Management
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    if (Cache::getInstance()) {
        Cache::clearTags(['system']);
        Messages::addSuccess('Cache with tag `system` cleaned');
    } else {
        Messages::addNotice('Cache is disabled');
    }
};
