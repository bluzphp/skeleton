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
use Bluz\Proxy\Layout;

return
/**
 * @param int $id
 *
 * @privilege ViewProfile
 *
 * @return \closure
 */
function ($id = null) {
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
        throw new Exception('User not found', 404);
    } else {
        $this->assign('user', $user);
    }
};
