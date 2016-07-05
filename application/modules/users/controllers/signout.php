<?php
/**
 * Logout proccess
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 * @return closure
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
        AppAuth\Table::getInstance()->removeCookieToken($this->user()->id);
        Auth::clearIdentity();
    }

    Messages::addNotice('You are signout');
    Response::redirectTo('index', 'index');
};
