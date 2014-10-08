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
use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Session;
use Bluz\Proxy\Request;

return
/**
 * @param string $login
 * @param string $password
 * @param bool $remember
 * @return \closure
 */
function ($login, $password, $rememberMe = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($this->user()) {
        Messages::addNotice('Already signed');
        $this->redirectTo('index', 'index');
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
                $ttl = Config::getModuleData('users', 'rememberMe');
                // TODO: remember me
                Session::setSessionCookieLifetime($ttl);
            }

            Messages::addNotice('You are signed');

            // try to rollback to previous called URL
            if ($rollback = Session::get('rollback')) {
                Session::delete('rollback');
                $this->redirect($rollback);
            }
            // try back to index
            $this->redirectTo('index', 'index');
        } catch (Exception $e) {
            Messages::addError($e->getMessage());
            $view->login = $login;
        } catch (AuthException $e) {
            Messages::addError($e->getMessage());
            $view->login = $login;
        }
    }
    // change layout
    $this->useLayout('small.phtml');
};
