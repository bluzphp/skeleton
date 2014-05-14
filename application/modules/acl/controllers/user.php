<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

return
/**
 * @privilege Edit
 *
 * @param int $id
 * @return void
 */
function ($id) use ($view) {
    /**
     * @var Bootstrap $this
     */
    $request = $this->getRequest();
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new Exception('User ID is incorrect');
    }

    if ($request->isPost()) {
        $roles = $request->getParam('roles');

        // update roles
        $this->getDb()->delete('acl_users_roles')
            ->where('userId = ?', $user->id)
            ->execute();

        foreach ($roles as $role) {
            $this->getDb()->insert('acl_users_roles')
                ->set('userId', $user->id)
                ->set('roleId', $role)
                ->execute();
        }

        // clean cache
        $this->getCache()->delete('user:'.$user->id);

        $this->getMessages()->addSuccess('User roles was updated');
    }

    /* @var $view \Bluz\View\View */
    $view->user = $user;

    $view->roles = Roles\Table::getInstance()->getRoles();
};
