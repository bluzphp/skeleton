<?php
/**
 * Ldap auth method
 *
 * @author   Anton Shevchuk
 * @created  07.11.12 16:40
 */
namespace Application;
use Bluz;
use Application\Users;

/**
 * @param string $username
 * @param string $password
 * @return \closure
 */
return function($username, $password) {
    /**
     * @var \Bluz\Application $this
     */
    $ldap = $this->getLdap();

    if ($ldap->checkAuth($username, $password)) {
        $user = Users\Table::findRowWhere(['login' => $username]);

        // get LDAP information
        $filter = "samaccountname=" . $username . "*";
        $attributes = array("displayname", "mail", "useraccountcontrol");
        $res = $ldap->processSearch($username, $password, $filter, $attributes);
        $info = $res[0];

        // useraccountcontrol == 66050 - disabled
        // useraccountcontrol == 66048 - active

        if (!$user) {
            // create new user
            $user = new Users\Row();
            $user->login = $username;
            $user->email = $info[1];
            $user->status = ($info[2] & 0x2)?Users\Row::STATUS_DISABLED:
                Users\Row::STATUS_ACTIVE;
            $user->save();

            // set default role
            $user2role = new UsersToRoles\Row();
            $user2role -> userId = $user->id;
            $user2role -> roleId = 2;
            $user2role -> save();

            $user->login();
        } else {
            // update user information from LDAP
            $user->email = $info[1];
            $user->status = ($info[2] & 0x2)?Users\Row::STATUS_DISABLED:
                Users\Row::STATUS_ACTIVE;
            $user->save();

            // try to login
            $user->login();
        }
        return true;
    } else {
        return false;
    }
};