<?php
/**
 * Login module/controller
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */
namespace Application;
use Bluz;
use Application\Auth;
return
/**
 * @param $login
 * @param $password
 * @return \closure
 */
function($login, $password) use ($identity, $view) {
    /**
     * @var Bluz\Application $this
     * @var Bluz\View\View $view
     */
    if ($identity) {
        $this->getMessages()->addNotice('Already signed');
        $this->redirectTo('index', 'index');
    } elseif ($this->getRequest()->isPost()) {
        try {
            if (empty($login)) {
                throw new Exception("Login is empty");
            }

            if (empty($password)) {
                throw new Exception("Password is empty");
            }

            // login/password
            Auth\Table::getInstance()->authenticateEquals($login, $password);

            // ldap
//            $ldapAuth = $this->api('ldap', 'auth');
//            if (!$ldapAuth($login, $password)) {
//                throw new Exception('Wrong credentials');
//            }

            $this->getMessages()->addNotice('You are signed');
            $this->redirectTo('index', 'index');
        } catch (\Exception $e) {
            $this->getMessages()->addError($e->getMessage());
            $view->login = $login;
        }
    }
    // change layout
    $this->useLayout('small.phtml');
};