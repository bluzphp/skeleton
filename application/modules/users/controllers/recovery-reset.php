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

return
/**
 *
 * @param int $id User UID
 * @param string $code
 * @param string $password
 * @param string $password2
 * @return \closure
 */
function ($id, $code, $password = null, $password2 = null) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    // change layout
    $this->useLayout('small.phtml');

    $actionRow = UsersActions\Table::findRow(['userId' => $id, 'token' => $code]);

    $datetime1 = new \DateTime(); // now
    $datetime2 = new \DateTime($actionRow->expired);
    $interval = $datetime1->diff($datetime2);

    if (!$actionRow or $actionRow->action !== UsersActions\Table::ACTION_RECOVERY) {
        $this->getMessages()->addError('Invalid code');
        $this->redirectTo('index', 'index');
    } elseif ($interval->invert) {
        $this->getMessages()->addError('The activation code has expired');
        $actionRow->delete();
        $this->redirectTo('index', 'index');
    } else {

        $user = Users\Table::findRow($id);
        $view->user = $user;
        $view->code = $code;

        if ($this->getRequest()->isPost()) {
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
                $this->getMessages()->addSuccess(
                    "Your password has been updated"
                );
                $this->redirectTo('users', 'signin');
            } catch (Exception $e) {
                $this->getMessages()->addError($e->getMessage());
            }
        }
    }
};
