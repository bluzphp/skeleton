<?php
/**
 * Logout proccess
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 * @return closure
 */
namespace Bluz;
use Bluz\Debug;
return
/**
 * @param $login
 * @param $password
 * @return closure
 */
function() use ($view) {
    /**
     * @var closure $bootstrap
     * @var Application $this
     * @var Auth\AbstractAdapter $auth
     */
    $this->getAuth()->setIdentity(null);
    $this->getMessages()->addNotice('You are signout');
    $this->redirectTo('index', 'index');

    return false;
};