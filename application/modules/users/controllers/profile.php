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
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->title = 'User Profile';

    $cache = $this->getCache();

    /**
     * @var \Bluz\Db\Rowset $userRows
     */
    $user = ModelManager::get('Users', $id);
    $user->getData();

    $view->user = $user;
};