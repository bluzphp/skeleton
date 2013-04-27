<?php
/**
 * User Activation
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  05.12.12 15:17
 */
namespace Application;

use Application\Users;
use Application\UsersActions;
use Application\Roles;
use Application\UsersToRoles;
use Bluz\Application\RedirectException;

return
    /**
     *
     * @param int $id User UID
     * @param string $code
     * @return \closure
     */
    function ($id, $code) {
        /**
         * @var \Bluz\Application $this
         * @var \Bluz\View\View $view
         */
        $actionRow = UsersActions\Table::findRow([
            'userId' => $id,
            'token' => $code
        ]);

        if (!$actionRow) {
            $this->getMessages()->addError('Invalid activation code');
            $this->redirectTo('index', 'index');
            return false;
        }

        $datetime1 = new \DateTime(); // now
        $datetime2 = new \DateTime($actionRow->expired);
        $interval = $datetime1->diff($datetime2);

        if ($actionRow->action !== UsersActions\Row::ACTION_ACTIVATION) {
            $this->getMessages()->addError('Invalid activation code');
        } elseif ($interval->invert) {
            $this->getMessages()->addError('The activation code has expired');
            $actionRow->delete();
        } else {
            // change user status
            $userRow = Users\Table::findRow($id);
            $userRow -> status = Users\Row::STATUS_ACTIVE;
            $userRow -> save();

            // create user role
            // get member role
            // FIXME f*cking magic
            $roleRow = Roles\Table::findRowWhere(['name' => Roles\Row::BASIC_MEMBER]);
            // create relation user to role
            $usersRoleRow = new UsersToRoles\Row();
            $usersRoleRow->roleId = $roleRow->id;
            $usersRoleRow->userId = $userRow->id;
            $usersRoleRow->save();

            // remove old code
            $actionRow->delete();
            $this->getMessages()->addSuccess(
                'Your Account has been successfully activated. <br/>'.
                'You can now log in using the username and password you chose during the registration.');
            $this->redirectTo('users', 'signin');
        }
        $this->redirectTo('index', 'index');
        return false;
    };