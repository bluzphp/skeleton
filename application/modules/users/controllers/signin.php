<?php
/**
 * Login module/controller
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */
namespace Application;

use Application\Auth;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Session;

/**
 * @param string $login
 * @param string $password
 * @param bool $rememberMe
 * @return array
 */
return function ($login, $password, $rememberMe = false) {
    /**
     * @var Controller $this
     */

    // change layout
    $this->useLayout('small.phtml');

    if ($this->user()) {
        Messages::addNotice('Already signed');
        Response::redirectTo('index', 'index');
    } elseif (Request::isPost()) {
        try {
            if (empty($login)) {
                throw new Exception("Login is empty");
            }

            if (empty($password)) {
                throw new Exception("Password is empty");
            }

            // login/password
            // throw AuthException
            Auth\Table::getInstance()->authenticateEquals($login, $password);

            if ($rememberMe) {
                Auth\Table::getInstance()->generateCookie();
            }

            Messages::addNotice('You are signed');

            // try to rollback to previous called URL
            if ($rollback = Session::get('rollback')) {
                Session::delete('rollback');
                Response::redirect($rollback);
            }
            // try back to index
            Response::redirectTo('index', 'index');
        } catch (Exception $e) {
            Messages::addError($e->getMessage());
            return ['login' => $login];
        } catch (AuthException $e) {
            Messages::addError($e->getMessage());
            return ['login' => $login];
        }
    }
};
