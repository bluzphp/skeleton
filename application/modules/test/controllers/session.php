<?php
/**
 * Default module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Session;

/**
 * @return array
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Session',
        ]
    );
    Layout::title("Test/Index");

    Session::set('test', Session::get('test') ?: 'Session time: '.date("H:i:s"));

    return [
        'title' => Layout::title(),
        'session' => Session::get('test'),
    ];

    //    if ($identity = $app->user()) {
    //        var_dump($acl->isAllowed('index/index', $identity['sid']));
    //        var_dump($acl->isAllowed('index/test', $identity['sid']));
    //        var_dump($acl->isAllowed('index/error', $identity['sid']));
    //    } else {
    //        Auth::authenticate('admin', '123456');
    //    }
};
