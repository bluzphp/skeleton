<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Db;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Management
 *
 * @param int $id
 * @return bool
 * @throws Exception
 */
return function ($id) {
    /**
     * @var Controller $this
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

    $this->assign('user', $user);
    $this->assign('roles', Roles\Table::getInstance()->getRoles());
};
