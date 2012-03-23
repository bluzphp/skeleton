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
function($id) use ($app, $view) {
    /**
     * @var \Bluz\Application $app
     * @var \Bluz\View $view
     */
    $app->getLayout()->title = 'User Profile';

    $cache = $app->getCache();

    /**
     * @var \Bluz\Db\Rowset $userRows
     */
    if (!$userRow = $cache->get('UserID:' . $id)) {
        $user = ModelManager::get('Application\\Users\\Model', $id);
        $cache->set('UserID:'.$id, $user, 30);
    }
    $view->user = $user;

};