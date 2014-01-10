<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

return
/**
 * get current user name
 *
 * this is example of custom layout helper
 *
 * @return string
 */
function () {
    /* @var \Application\Users\Row $user */
    $user = app()->getAuth() ? app()->getAuth()->getIdentity() : null;
    if ($user) {
        return $user->login;
    } else {
        return 'Guest';
    }
};
