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
 * @return \closure
 */
function($key = null) use ($view) {
    /**
     * @var Bluz\Application $this
     * @var Bluz\View\View $view
     */
    $view->title('Bookmarklets');
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        $view->ahref('System', ['system', 'index']),
        'Bookmarklets',
    ]);

    $key = $key?:'BLUZ_DEBUG';
    return ['key' => $key];
};
