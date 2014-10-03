<?php
/**
 * Logout proccess
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 * @return closure
 */
namespace Application;

use Bluz\Proxy\Auth;
use Bluz\Proxy\Messages;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Auth::clearIdentity();
    Messages::addNotice('You are signout');
    $this->redirectTo('index', 'index');
};
