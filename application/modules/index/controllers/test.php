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
function($id) use ($bootstrap, $view) {

    $this->getLayout()->title = 'Custom Title';

    $view->title = "Index/Test";
    /* @var \Bluz\Application $this */
//    $this->getMessages()->addNotice('Notice');
//    $this->getMessages()->addError('Warning<br/>Second Line');
//    $this->getMessages()->addError('Error');
    //$view->reload = true;

    $cache = $this->getCache();
    /**
     * @var Rowset $userRows
     */
    if (! $userRow = $cache->get('UserID:'.$id)) {
        $userRow = Users\Table::getInstance()->findRow($id);
        $cache->set('UserID:'.$id, $userRow, 30);

    }
    $view->user = $userRow;

};