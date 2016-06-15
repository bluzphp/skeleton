<?php
/**
 * Example of Cache usage
 *
 * @category Example
 *
 * @author   dark
 * @created  08.07.11 13:23
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Layout;

/**
 * @param  int $id
 * @return void
 * @throws \Exception
 */
return function ($id = null) {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Cache Data',
        ]
    );
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
    $this->assign('user', $userRow);
};
