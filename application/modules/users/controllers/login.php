<?php
/**
 * Login module/controller
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
function($login, $password) use ($view) {
    /**
     * @var closure $bootstrap
     * @var Application $this
     * @var Auth $auth
     * @var View $view
     */
    if ($identity = $this->getAuth()->getIdentity()) {
        $this->getMessages()->addNotice('Already signed');
        $this->redirectTo('index', 'index');
    } elseif ($this->getRequest()->isPost()) {
        try {
            \Application\Users\Table::getInstance()->login($login, $password);

            //$this->getAuth()->authenticate($login, $password);
            $this->getMessages()->addNotice('You are signed');
            $this->redirectTo('index', 'index');
        } catch (Exception $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
    // change layout
    $this->useLayout('small.phtml');
};