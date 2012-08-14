<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */
namespace Application;

return
/**
 * @privilege Edit
 *
 * @return \closure
 */
function($id) use ($request, $view) {
    $user = Users\Table::getInstance()->findRow($id);

    if (!$user) {
        throw new Exception('User ID "'.$id.'" is incorrect');
    }

    /*@var $request \Bluz\Request\AbstractRequest */
    if ($request->isPost()) {
        $roles = $request->getParam('roles');

        // update roles
        $this->getDb()->delete('acl_usersToRoles', ['userId' => $user->id]);

        foreach ($roles as $role) {
            $this->getDb()->insert('acl_usersToRoles', ['userId' => $user->id, 'roleId' => $role]);
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