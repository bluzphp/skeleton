<?php

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Layout;

/**
 * @privilege Management
 *
 * @param $name
 *
 * @return void
 * @throws NotFoundException
 */
return function ($name) {
    /**
     * @var Controller $this
     */
    Layout::title('Logs');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('Logs', ['system', 'logs']),
            __('Log file'),
        ]
    );

    $file = realpath(PATH_DATA . '/logs/' . $name);

    if (strpos($file, PATH_DATA . '/logs/') !== 0 || !file_exists($file)) {
        throw new NotFoundException();
    }

    $this->assign('file', $file);
};
