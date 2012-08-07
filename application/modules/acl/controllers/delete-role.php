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
function($id) use ($view) {

    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     * @var Roles\Row $role
     */
    $role = Roles\Table::getInstance()->findRow($id);

    if (!$role) {
        throw new Exception('Role ID "'.$id.'" is incorrect');
    }

    if ($role->isBasic()) {
        throw new Exception('Cannot modify this role');
    }

    $role->delete();

    $this->getMessages()->addNotice('Role was removed');
    $this->redirectTo('acl', 'index');
};