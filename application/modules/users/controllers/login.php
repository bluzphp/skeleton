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
 * @resource /users/login
 *
 * @param $login
 * @param $password
 * @return closure
 */
function($login, $password) use ($bootstrap, $app, $view) {
    /**
     * @var closure $bootstrap
     * @var Application $app
     * @var Auth $auth
     * @var View $view
     */
    if ($identity = $app->getAuth()->getIdentity()) {
        $app->addNotice('Already signed');
        $app->redirectTo('index', 'index');
    } elseif ($app->getRequest()->isPost()) {
        try {
            \Application\Users\Table::getInstance()->login($login, $password);

            //$app->getAuth()->authenticate($login, $password);
            $app->addNotice('You are signed');
            $app->redirectTo('index', 'index');
        } catch (Exception $e) {
            $app->addError($e->getMessage());
        }
    }
    // change layout
    $app->useLayout('small.phtml');
};