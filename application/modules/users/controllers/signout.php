<?php
/**
 * Logout proccess
 *
 * @return closure
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */

namespace Application;

use Application\Auth as AppAuth;
use Bluz\Controller\Controller;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    if ($this->user()) {
        AppAuth\Provider\Cookie::remove($this->user()->getId());
        Auth::clearIdentity();
    }

    Messages::addNotice('You are signout');
    Response::redirectTo('index', 'index');
};
