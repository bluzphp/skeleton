<?php
/**
 * Reset password procedure
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  11.12.12 15:25
 */
namespace Application;

use Application\Auth;
use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @param int $id User UID
 * @param string $code
 * @param string $password
 * @param string $password2
 */
return function ($id, $code, $password = null, $password2 = null) {
    /**
     * @var Controller $this
     */
    // change layout
    $this->useLayout('small.phtml');

    $actionRow = UsersActions\Table::findRow(['userId' => $id, 'code' => $code]);

    $datetime1 = new \DateTime(); // now
    $datetime2 = new \DateTime($actionRow->expired);
    $interval = $datetime1->diff($datetime2);

    if (!$actionRow or $actionRow->action !== UsersActions\Table::ACTION_RECOVERY) {
        Messages::addError('Invalid code');
        Response::redirectTo('index', 'index');
    } elseif ($interval->invert) {
        Messages::addError('The activation code has expired');
        $actionRow->delete();
        Response::redirectTo('index', 'index');
    } else {
        $user = Users\Table::findRow($id);
        
        $this->assign('user', $user);
        $this->assign('code', $code);

        if (Request::isPost()) {
            try {
                if (empty($password) or empty($password2)) {
                    throw new Exception('Please enter your new password');
                }

                if ($password != $password2) {
                    throw new Exception('Please repeat your new password');
                }

                // remove old auth record
                if ($oldAuth = Auth\Table::getInstance()->getAuthRow(Auth\Table::PROVIDER_EQUALS, $user->login)) {
                    $oldAuth -> delete();
                }

                // create new auth record
                Auth\Table::getInstance()->generateEquals($user, $password);

                // show notification and redirect
                Messages::addSuccess(
                    "Your password has been updated"
                );
                Response::redirectTo('users', 'signin');
            } catch (Exception $e) {
                Messages::addError($e->getMessage());
            }
        }
    }
};
