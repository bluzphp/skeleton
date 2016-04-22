<?php
/**
 * Test MVC
 *
 * @author   dark
 * @created  08.07.11 13:23
 */
namespace Application;

use Bluz\Proxy\Cache;
use Bluz\Proxy\Layout;

return
/**
 * @param int $id
 *
 * @acl Index Test
 *
 * @return \closure
 */
function ($id = null) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Cache Data',
        ]
    );
    /* @var Bootstrap $this */
    Layout::title('Check cache');

    // try to load profile of current user
    if (!$id && $this->user()) {
        $id = $this->user()->id;
    }

    if (!$id) {
        throw new \Exception('User not found', 404);
    }

    /**
     * @var Users\Row $userRow
     */
    if (!$userRow = Cache::get('user:'.$id)) {
        $userRow = Users\Table::findRow($id);
        Cache::set('user:'.$id, $userRow, 30);
    };

    if (!$userRow) {
        throw new \Exception('User not found', 404);
    }
    $view->user = $userRow;
};
