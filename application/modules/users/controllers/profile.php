<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */

namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Layout;

/**
 * @privilege ViewProfile
 *
 * @param int $id
 *
 * @throws NotFoundException
 */
return function ($id = null) {
    /**
     * @var Controller $this
     */
    Layout::title('User Profile');

    // try to load profile of current user
    if (!$id && $this->user()) {
        $id = $this->user()->id;
    }

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new NotFoundException('User not found');
    }
    $this->assign('user', $user);
};
