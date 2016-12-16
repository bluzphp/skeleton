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
     * Get profile link by email user
     *
     * @param string $email
     * @param array $attributes
     * @return string
     */
    function ($email, array $attributes = []) {
        /**
         * @var \Application\Users\Row $user
         */
        $row = Users\Table::findRow(['email' => $email]);

        if (!empty($row)) {
            $attributes['data-id'] = $row->id;
            return $this->ahref($row->login, ['users', 'profile'], $attributes);
        }
        return '';
    };
