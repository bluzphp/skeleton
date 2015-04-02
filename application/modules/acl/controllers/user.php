<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Cache;
use Bluz\Proxy\Db;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

return
/**
 * @privilege Management
 *
 * @accept HTML
 * @accept JSON
 * @param int $id
 * @return void
 */
function ($id) use ($view) {
    /**
     * @var Bootstrap $this
     */
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new Exception('User ID is incorrect');
    }

    if (Request::isPost()) {
        $roles = Request::getParam('roles');

        // update roles
        Db::delete('acl_users_roles')
            ->where('userId = ?', $user->id)
            ->execute();

        foreach ($roles as $role) {
            Db::insert('acl_users_roles')
                ->set('userId', $user->id)
                ->set('roleId', $role)
                ->execute();
        }

        // clean cache
        Cache::delete('user:'.$user->id);
        Messages::addSuccess('User roles was updated');
        return false;
    }

    /* @var $view \Bluz\View\View */
    $view->user = $user;

    $view->roles = Roles\Table::getInstance()->getRoles();
};
