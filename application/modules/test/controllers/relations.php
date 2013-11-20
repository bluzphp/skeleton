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
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Relations::addClassMap('pages', '\\Application\\Pages\\Table');
    Relations::addClassMap('users', '\\Application\\Users\\Table');
    Relations::setRelation('pages', 'userId', 'users', 'id');

    /* @var Pages\Row */
    $page = Pages\Table::findRow(5);

    $relations = Relations::getRelations('pages', 'users');

    $field = $relations['pages'];

    $key = $page->{$field};

    $result = Relations::findRelations('pages', 'users', [$key]);

    var_dump($result);

    $user = Users\Table::findRow(1);

    $relations = Relations::getRelations('pages', 'users');

    $field = $relations['users'];

    $key = $user->{$field};

    $result = Relations::findRelations('users', 'pages', [$key]);

    var_dump($result);

    return false;
};
