<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */
namespace Application;


return
/**
 * @privilege View
 *
 *
 * @return \closure
 */
function() use ($view) {

    /**
     * @var Bootstrap $app
     */
//    $view->users = Users\Table::getInstance()->fetchAll();
    $view->users = $this->getDb()->fetchObjects('
        SELECT u.*, GROUP_CONCAT( ar.`name` SEPARATOR ", " ) AS roles
        FROM users u, acl_roles ar, acl_usersToRoles aur
        WHERE
            u.`id` = aur.`userId`
        AND ar.`id` = aur.`roleId`', [], '\Application\Users\Row');

    $this->getLayout()->breadCrumbs([
        $view->ahref('ACL', ['acl', 'index']),
        'Users',
    ]);
};