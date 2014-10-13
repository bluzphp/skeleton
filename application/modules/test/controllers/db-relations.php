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
use Bluz\Proxy\Db;
use Bluz\Proxy\Layout;

return
/**
 * @return bool
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'DB Relations',
        ]
    );

    // Db table to class relation
    Relations::addClassMap('pages', '\\Application\\Pages\\Table');
    Relations::addClassMap('users', '\\Application\\Users\\Table');
    Relations::addClassMap('acl_roles', '\\Application\\Roles\\Table');
    Relations::addClassMap('acl_users_roles', '\\Application\\UsersRoles\\Table');

    // one to many relation
    Relations::setRelation('pages', 'userId', 'users', 'id');

    // many to many relation
    Relations::setRelation('acl_users_roles', 'roleId', 'acl_roles', 'id');
    Relations::setRelation('acl_users_roles', 'userId', 'users', 'id');

    Relations::setRelations('acl_roles', 'users', ['acl_users_roles']);

    /* @var Pages\Row */
    $page = Pages\Table::findRow(5);
    $result = $page->getRelation('users');

    echo "<h2>Page Owner</h2>";
    var_dump(current($result));

    $user = Users\Table::findRow(1);
    $result = $user->getRelation('pages');

    echo "<h2>User pages</h2>";
    var_dump($result);

    $result = $user->getRelation('acl_roles');

    echo "<h2>User roles</h2>";
    var_dump($result);


    $result = Db::fetchRelations(
        "SELECT '__users', u.*, '__pages', p.* FROM users u LEFT JOIN pages p ON p.userId = u.id"
    );

    echo "<h2>User - Page relation</h2>";
    var_dump($result);
    return false;
};
