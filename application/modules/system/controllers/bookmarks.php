<?php
/**
 * Debug bookmarklet
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz;

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
    $view->title('Bookmarklets');
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('System', ['system', 'index']),
            __('Bookmarklets'),
        ]
    );

    $key = defined('DEBUG_KEY') ? DEBUG_KEY : 'BLUZ_DEBUG';
    return ['key' => $key];
};
