<?php
/**
 * Logout proccess
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 * @return closure
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Messages;
use Application\Auth as AppAuth;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var Controller $this
     */
    AppAuth\Table::getInstance()->removeCookieToken($this->user()->id);
    Auth::clearIdentity();
    Messages::addNotice('You are signout');
    $this->redirectTo('index', 'index');
};
