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
 * Flush cache servers
 *
 * @privilege Management
 * @return void
 */
function () {
    /**
     * @var Bootstrap $this
     */
    if ($handler = $this->getCache()->getAdapter()) {
        $this->getCache()->getAdapter()->flush();
        $this->getCache()->getTagAdapter()->flush();
        $this->getMessages()->addSuccess("Cache is flushed");
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
    }
};
