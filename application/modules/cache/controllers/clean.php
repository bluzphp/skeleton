<?php
/**
 * Clean personal cache
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */
namespace Application;
use Bluz;

return
/**
 * Clean data
 *
 * @privilege Management
 * @return \closure
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $userId = $this->getAuth()->getIdentity()->id;
    if ($handler = $this->getCache()->isEnabled()) {
        $this->getCache()->delete('roles:'.$userId);
        $this->getCache()->delete('privileges:'.$userId);
        $this->getMessages()->addSuccess("Cache is cleaned");
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
    }
};