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
 * @resource /users/logout
 *
 * @param $login
 * @param $password
 * @return closure
 */
function() use ($bootstrap, $app, $view) {
    /**
     * @var closure $bootstrap
     * @var Application $app
     * @var Auth $auth
     */
    $app->getAuth()->setIdentity(null);
    $app->addNotice('You are signout');
    $app->redirectTo('index', 'index');

    return false;
};