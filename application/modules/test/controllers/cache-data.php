<?php
/**
 * Test MVC
 *
 * @author   dark
 * @created  08.07.11 13:23
 */
namespace Application;

use Bluz;

return
/**
 * @param int $id
 *
 * @acl Index Test
 *
 * @return \closure
 */
function ($id = null) use ($bootstrap, $view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Cache Data',
        ]
    );
    /* @var \Bluz\Application $this */
    $this->getLayout()->title('Check cache');

    $view->title = "Index/Test";

    // try to load profile of current user
    if (!$id && $this->getAuth()->getIdentity()) {
        $id = $this->getAuth()->getIdentity()->id;
    }

    if (!$id) {
        throw new \Exception('User not found', 404);
    }

    $cache = $this->getCache();
    /**
     * @var Bluz\Db\Rowset $userRows
     */
    if (!$userRow = $cache->get('user:'.$id)) {
        $userRow = Users\Table::findRow($id);
        $cache->set('user:'.$id, $userRow, 30);
    };

    if (!$userRow) {
        throw new \Exception('User not found', 404);
    }
    $view->user = $userRow;
};
