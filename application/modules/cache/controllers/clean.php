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

use Bluz;

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
    if ($handler = $this->getCache()->getAdapter()) {
        // routers
        $this->getCache()->delete('router:routers');
        $this->getCache()->delete('router:reverse');
        // roles
        $this->getCache()->deleteByTag('roles');
        $this->getCache()->deleteByTag('privileges');
        // reflection data
        $this->getCache()->deleteByTag('reflection');
        // db metadata
        $this->getCache()->deleteByTag('db');

        $this->getMessages()->addSuccess("Cache is cleaned");
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
    }
};
