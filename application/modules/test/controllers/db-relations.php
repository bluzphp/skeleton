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

    /* @var Pages\Row */
    $page = Pages\Table::findRow(5);
    $user = $page->getRelation('Users');

    echo "<h2>Page Owner</h2>";
    var_dump($user);

    $pages = $user->getRelations('Pages');

    echo "<h2>User pages</h2>";
    var_dump(sizeof($pages));

    $roles = $user->getRelations('Roles');

    echo "<h2>User roles</h2>";
    var_dump($roles);

    echo "<h2>User with all relations</h2>";
    var_dump($user);

    $result = Db::fetchRelations(
        "SELECT '__users', u.*, '__pages', p.* FROM users u LEFT JOIN pages p ON p.userId = u.id"
    );

    echo "<h2>User - Page relation</h2>";
    var_dump($result);

    return false;
};
