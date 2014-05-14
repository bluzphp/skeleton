<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

return
/**
 * Get current user name
 *
 * @var \Application\Users\Row $user
 * @return string
 */
function () {
    $user = app()->getAuth() ? app()->getAuth()->getIdentity() : null;
    if ($user) {
        return $user->login;
    } else {
        return __('Guest');
    }
};
