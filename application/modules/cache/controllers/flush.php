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
use Bluz\Proxy\Messages;

return
/**
 * Flush cache servers
 *
 * @privilege Management
 * @return void
 */
function () {
    /**
     * @var Bootstrap $this
     */
    if (!Cache::getInstance() instanceof Nil) {
        Cache::flush();
        Messages::addSuccess("Cache is flushed");
    } else {
        Messages::addNotice("Cache is disabled");
    }
};
