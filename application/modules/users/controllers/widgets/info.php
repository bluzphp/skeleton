<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Db;

/**
 * @privilege ViewProfile
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->assign('user', $this->user());
};
