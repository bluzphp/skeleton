<?php
/**
 * Build list of routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */

/**
 * @namespace
 */
namespace Application;

use Bluz;

return
/**
 * List of cache servers
 *
 * @privilege Management
 * @return void
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Cache')
        ]
    );

    if ($handler = $this->getCache()->getAdapter()) {
        $view->adapter = get_class($handler);
    } else {
        $view->adapter = null;
        $this->getMessages()->addNotice("Cache is disabled");
    }
};
