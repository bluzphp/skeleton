<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Layout\Helper;

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
    if ($user = app()->user()) {
        return $user->login;
    } else {
        return __('Guest');
    }
};
