<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */
namespace Application;

return
/**
 * @param int $id
 *
 * @privilege ViewUserProfile
 *
 * @return closure
 */
function($id) use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View $view
     */
    $this->getLayout()->title = 'User Profile';

    $cache = $this->getCache();

    /**
     * @var \Bluz\Db\Rowset $userRows
     */
    if (!$userRow = $cache->get('UserID:' . $id)) {
        $user = Users\Table::getInstance()->find($id);
        $cache->set('UserID:'.$id, $user, 30);
    }
    $view->user = $user;

};