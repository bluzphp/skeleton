<?php
/**
 * Login module/controller
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */
namespace Application;

use Bluz;
use Bluz\Auth\AuthException;
use Application\Auth;

return
/**
 * @param string $login
 * @param string $password
 * @return \closure
 */
function ($login, $password) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    if ($this->user()) {
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

            $this->getMessages()->addNotice('You are signed');

            // try to rollback to previous called URL
            if ($rollback = $this->getSession()->rollback) {
                unset($this->getSession()->rollback);
                $this->redirect($rollback);
            }
            // try back to index
            $this->redirectTo('index', 'index');
        } catch (Exception $e) {
            $this->getMessages()->addError($e->getMessage());
            $view->login = $login;
        } catch (AuthException $e) {
            $this->getMessages()->addError($e->getMessage());
            $view->login = $login;
        }
    }
    // change layout
    $this->useLayout('small.phtml');
};
