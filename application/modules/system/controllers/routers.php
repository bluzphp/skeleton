<?php
/**
 * Build list of routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */
namespace Application;

use Bluz;

/**
 * @privilege Info
 *
 * @return \closure
 */
return
/**
 * List of custom routers
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $view->title('Routers Map');
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            $view->ahref('System', ['system', 'index']),
            __('Routers Map'),
        ]
    );
    $routers = array();
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = pathinfo(dirname(dirname($file->getPathname())), PATHINFO_FILENAME);
        $controller = pathinfo($file->getPathname(), PATHINFO_FILENAME);
        $data = $this->reflection($file->getPathname());
        if (isset($data['route'])) {
            if (!isset($routers[$module])) {
                $routers[$module] = array();
            }

            $routers[$module][$controller] = ['route' => $data['route'], 'params' => $data['params']];
        }
    }
    $view->routers = $routers;
};
