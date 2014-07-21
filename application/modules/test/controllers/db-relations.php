<?php
/**
 * Example of Db\Relations
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  14.11.13 10:45
 */
namespace Application;

use Bluz\Db\Relations;

return
/**
 * @return bool
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'DB Relations',
        ]
    );

    Relations::addClassMap('pages', '\\Application\\Pages\\Table');
    Relations::addClassMap('users', '\\Application\\Users\\Table');
    Relations::addClassMap('acl_roles', '\\Application\\Roles\\Table');
    Relations::addClassMap('acl_users_roles', '\\Application\\UsersRoles\\Table');

    Relations::setRelation('pages', 'userId', 'users', 'id');

    Relations::setRelation('acl_users_roles', 'roleId', 'acl_roles', 'id');
    Relations::setRelation('acl_users_roles', 'userId', 'users', 'id');

    Relations::setRelations('acl_roles', 'users', ['acl_users_roles']);

    /* @var Pages\Row */
    $page = Pages\Table::findRow(5);
    $result = $page->getRelation('users');
    var_dump("Page owner:", $result);

    $user = Users\Table::findRow(1);
    $result = $user->getRelation('pages');
    var_dump("User pages:", $result);

    $result = $user->getRelation('acl_roles');
    var_dump("User roles:", $result);
    return false;
};
