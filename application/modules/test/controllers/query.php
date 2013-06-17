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

    $selectBuilder = new SelectBuilder();

    $selectBuilder = $selectBuilder
        ->select('u.*', 'ua.*')
        ->from('users', 'u')
        ->leftJoin('u', 'users_actions', 'ua', 'ua.userId = u.id')
        ->where('u.id = ? OR u.id = ?', 4, 5)
        ->orWhere('u.id IN (?)', [4, 5])
        ->andWhere('u.status = ? OR u.status = ?', 'active', 'pending')
        ->orWhere('u.login LIKE (?)', 'A%')
        ->limit(5)
    ;
    debug($selectBuilder->getQuery());
//    debug($selectBuilder->execute());
//    debug($selectBuilder->execute('\\Application\\Users\\Row'));

    $insertBuilder = new InsertBuilder();
    $insertBuilder = $insertBuilder
        ->insert('users`')
        ->set('login', 'example')
        ->set('email', 'example@domain.com')
    ;
    debug($insertBuilder->getQuery());

    $updateBuilder = new UpdateBuilder();
    $updateBuilder = $updateBuilder
        ->update('users')
        ->setArray(
            [
                'status' => 'active',
                'updated' => date('Y-m-d H:i:s')
            ]
        )
        ->where('id = ?', 30)
    ;
    debug($updateBuilder->getQuery());

    $deleteBuilder = new DeleteBuilder();
    $deleteBuilder
        ->delete('users')
        ->where('id = ?', 5)
        ->limit(1)
    ;
    debug($deleteBuilder->getQuery());
    return false;
};
 
