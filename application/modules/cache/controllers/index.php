<?php
/**
 * Build list of routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */
namespace Application;
use Bluz;

return
/**
 * List of cache servers
 * @privilege Management
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs([
        $view->ahref('Dashboard', ['dashboard', 'index']),
        'Cache'
    ]);

    if ($handler = $this->getCache()->getAdapter()) {
        // TODO: show active cache handlers
    } else {
        $this->getMessages()->addNotice("Cache is disabled");
    }
};