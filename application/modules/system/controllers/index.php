<?php
/**
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $view->title('System Module');
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('System'),
        ]
    );
};
