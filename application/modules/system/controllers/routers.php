<?php
/**
 * Build list of custom routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */
namespace Application;

use Bluz\Proxy\Layout;

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
    Layout::title('Routers Map');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
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
        $reflection = $this->reflection($file->getPathname());
        if ($route = $reflection->getRoute()) {
            if (!isset($routers[$module])) {
                $routers[$module] = array();
            }

            $routers[$module][$controller] = ['route' => $route, 'params' => $reflection->getParams()];
        }
    }
    $view->routers = $routers;
};
