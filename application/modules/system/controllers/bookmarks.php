<?php
/**
 * Debug bookmarklet
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @privilege Info
 *
 * @return array
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::title('Bookmarklets');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('System', ['system', 'index']),
            __('Bookmarklets'),
        ]
    );

    $key = getenv('BLUZ_DEBUG_KEY') ?: 'BLUZ_DEBUG';
    return ['key' => $key];
};
