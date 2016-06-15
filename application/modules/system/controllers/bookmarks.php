<?php
/**
 * Debug bookmarklet
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Info
 *
 * @return array
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Bookmarklets');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('System', ['system', 'index']),
            __('Bookmarklets'),
        ]
    );

    $key = getenv('BLUZ_DEBUG_KEY') ?: 'BLUZ_DEBUG';
    return ['key' => $key];
};
