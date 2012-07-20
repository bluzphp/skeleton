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
 * Flush cache servers
 * @privilege Management
 * @return \closure
 */
function () {
    if ($handler = $this->getCache()->handler()) {
        $this->getCache()->flush();
        $this->getMessages()->addSuccess("Cache is flushed");
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
    }
};