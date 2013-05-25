<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */
namespace Application;

return
/**
 * @param int $id
 * @privilege Edit
 *
 * @return \closure
 */
function($id) use ($view) {
    /**
     * @var \Bluz\Application $this
     */
    $request = $this->getRequest();
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new Exception('User ID is incorrect');
    }

    if ($request->isPost()) {
        $roles = $request->getParam('roles');

        // update roles
        $this->getDb()->delete('acl_users_roles', ['userId' => $user->id]);

        foreach ($roles as $role) {
            $this->getDb()->insert('acl_users_roles', ['userId' => $user->id, 'roleId' => $role]);
        }

        // clean cache
        $this->getCache()->delete('roles:'.$user->id);
        $this->getCache()->delete('privileges:'.$user->id);

        $this->redirectTo('acl', 'users');
    }

    /* @var $view \Bluz\View\View */
    $view->user = $user;

    $view->roles = Roles\Table::getInstance()->getRoles();
};