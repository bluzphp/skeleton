<?php
/**
 * Login module/controller
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */
namespace Application;
use Bluz;
use Application\Users;
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
            Users\Table::getInstance()->login($login, $password);
            //$this->getAuth()->authenticate($login, $password);
            $this->getMessages()->addNotice('You are signed');
            $this->redirectTo('index', 'index');
        } catch (\Exception $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
    // change layout
    $this->useLayout('small.phtml');
};