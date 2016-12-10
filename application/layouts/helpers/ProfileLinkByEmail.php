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

return
    /**
     * get profile link by email user
     *
     * @param string $name
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
            return '<a href = "' . $this->url('users', 'profile', ['id' => $row->id]) . '">' . $row->login . '</a>';
        }
        return false;
    };
