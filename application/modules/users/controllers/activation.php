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

use Application\Roles;
use Application\Roles\Table;
use Application\Users;
use Application\UsersActions;
use Application\UsersRoles;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;

/**
 * @param int $id User UID
 * @param string $code
 * @return bool
 */
return function ($id, $code) {
    /**
     * @var Controller $this
     */
    $actionRow = UsersActions\Table::findRow(['userId' => $id, 'code' => $code]);

    if (!$actionRow) {
        Messages::addError('Invalid activation code');
        Response::redirectTo('index', 'index');
        return false;
    }

    $datetime1 = new \DateTime(); // now
    $datetime2 = new \DateTime($actionRow->expired);
    $interval = $datetime1->diff($datetime2);

    if ($actionRow->action !== UsersActions\Table::ACTION_ACTIVATION) {
        Messages::addError('Invalid activation code');
    } elseif ($interval->invert) {
        Messages::addError('The activation code has expired');
        $actionRow->delete();
    } else {
        // change user status
        $userRow = Users\Table::findRow($id);
        $userRow -> status = Users\Table::STATUS_ACTIVE;
        $userRow -> save();

        // create user role
        // get member role
        $roleRow = Roles\Table::findRowWhere(['name' => Table::BASIC_MEMBER]);
        // create relation user to role
        $usersRoleRow = new UsersRoles\Row();
        $usersRoleRow->roleId = $roleRow->id;
        $usersRoleRow->userId = $userRow->id;
        $usersRoleRow->save();

        // remove old code
        $actionRow->delete();
        Messages::addSuccess(
            'Your Account has been successfully activated. <br/>'.
            'You can now log in using the username and password you chose during the registration.'
        );
        Response::redirectTo('users', 'signin');
    }
    Response::redirectTo('index', 'index');
};
