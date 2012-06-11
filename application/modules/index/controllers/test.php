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
function($id) use ($this, $bootstrap, $view) {

    /* @var \Bluz\Application $this */
    $this->getLayout()->title = 'Custom Title';

    $view->title = "Index/Test";
//    $this->getMessages()->addNotice('Notice');
    $this->getMessages()->addError('Warning<br/>Second Line');
//    $this->getMessages()->addError('Error');
    //$view->reload = true;

    $cache = $this->getCache();
    /**
     * @var \Bluz\Db\Rowset $userRows
     */
    if (! $userRow = $cache->get('UserID:'.$id)) {
        $usersTable = Users\Table::getInstance();
        $userRows = $usersTable -> find(array($id));
        $userRow = $userRows->current();
        $cache->set('UserID:'.$id, $userRow, 30);

    }
    $view->user = $userRow;

};