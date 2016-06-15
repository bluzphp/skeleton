<?php
/**
 * Example of Db\Relations
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  14.11.13 10:45
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Db;
use Bluz\Proxy\Layout;

/**
 * @return bool
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'DB Relations',
        ]
    );

    echo "<h2>Page Owner</h2>";
    /* @var Pages\Row */
    $page = Pages\Table::findRow(1);
    $user = $page->getRelation('Users');
    var_dump($user);

    echo "<h2>User pages</h2>";
    $pages = $user->getRelations('Pages');
    var_dump(sizeof($pages));

    echo "<h2>User roles</h2>";
    $roles = $user->getRelations('Roles');
    var_dump($roles);

    echo "<h2>User with all relations</h2>";
    var_dump($user);


    echo "<h2>User - Page relation</h2>";
    $result = Db::fetchRelations(
        "SELECT '__users', u.*, '__pages', p.* FROM users u LEFT JOIN pages p ON p.userId = u.id"
    );
    var_dump($result);

    return false;
};
