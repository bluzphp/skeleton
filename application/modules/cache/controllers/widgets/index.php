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
use Bluz\Proxy\Layout;
use Bluz\Proxy\Messages;

/**
 * List of cache servers
 *
 * @privilege Management
 * @return string
 */
return function () {
    /**
     * @var Controller $this
     */
    if ($cacheAdapter = Cache::getInstance()) {
        $adapter = preg_split('/\\\\/', get_class($cacheAdapter));
        $this->assign('adapter', array_pop($adapter));
    } else {
        $this->assign('adapter', $cacheAdapter);
    }
    return 'widgets/index.phtml';
};
