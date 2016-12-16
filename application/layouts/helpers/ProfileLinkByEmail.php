<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Layout\Helper;

use Application\Users;
use Bluz\Proxy\Layout;

return
    /**
     * get profile link by email user
     *
     * @param string $email
     *
     * @return string
     */
    function ($email) {
        /**
         * @var \Application\Users\Row $user
         */
        $row = Users\Table::findRow(['email' => $email]);

        if (!empty($row)) {
            return Layout::ahref($row->login, ['users', 'profile'], ['id' => $row->id]);
        }
        return false;
    };
