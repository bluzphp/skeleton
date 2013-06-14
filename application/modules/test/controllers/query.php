<?php
/**
 * Example of DB Query builder usage
 *
 * @author   Anton Shevchuk
 * @created  07.06.13 18:28
 */
namespace Application;

use \Bluz\Db\Query\SelectBuilder;
use \Bluz\Db\Query\InsertBuilder;
use \Bluz\Db\Query\UpdateBuilder;
use \Bluz\Db\Query\DeleteBuilder;

return
/**
 * @TODO: need more informative example
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     */
    debug(file_get_contents(__FILE__));

    $builer = new SelectBuilder();

    $build = $builer
        ->select('u.*', 'ua.*')
        ->from('users', 'u')
        ->leftJoin('u', 'users_actions', 'ua', 'ua.userId = u.id')
        ->where('u.id = ? OR u.id = ?', 4, 5)
        ->orWhere('u.id IN (?)', [4, 5])
        ->andWhere('u.status = ? OR u.status = ?', 'active', 'pending')
        ->orWhere('u.login LIKE (?)', 'A%')

    ;
    debug($build->getSql());
    debug($build->getQuery());
    debug($build->execute());

    return false;
};
 
