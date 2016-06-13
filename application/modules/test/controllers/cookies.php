<?php
/**
 * Cookies example
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Response;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Cookies',
        ]
    );

    // use default function
    setcookie('hello', 'world');
    setcookie('time', 'current');

    // use Response object
    Response::setCookie('response', 'call');
    Response::setCookie('time', 'hour', time() + 3600);

    $this->disableView();
};
