<?php
/**
 * Test MVC
 *
 * @author   dark
 * @created  08.07.11 13:23
 */
namespace Application;
return
/**
 * @param int $id
 *
 * @acl Index Test
 *
 * @return closure
 */
function($id) use ($app, $bootstrap, $view) {

    $app->getLayout()->title = 'Custom Title';

    $view->title = "Index/Test";
    /* @var \Bluz\Application $app */
//    $app->addNotice('Notice');
    $app->addError('Warning<br/>Second Line');
//    $app->addError('Error');
    //$view->reload = true;

    $cache = $app->getCache();
    /**
     * @var Rowset $userRows
     */
    if (! $userRow = $cache->get('UserID:'.$id)) {
        $usersTable = Users\Table::getInstance();
        $userRows = $usersTable -> find(array($id));
        $userRow = $userRows->current();
        $cache->set('UserID:'.$id, $userRow, 30);

    }
    $view->user = $userRow;

};