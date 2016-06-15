<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Layout\Helper;

use Bluz\Proxy\Auth;

return
/**
 * Get current user name
 *
 * @return string
 */
function () {
    /**
     * @var \Application\Users\Row $user
     */
    if ($user = Auth::getIdentity()) {
        return $user->login;
    } else {
        return __('Guest');
    }
};
