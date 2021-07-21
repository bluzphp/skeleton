<?php

/**
 * Build list of custom routers
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 12:27
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Info
 *
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Routers Map');
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            Layout::ahref('System', ['system', 'index']),
            __('Routers Map'),
        ]
    );
    $routers = [];
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = pathinfo(dirname($file->getPathname(), 2), PATHINFO_FILENAME);
        $controller = pathinfo($file->getPathname(), PATHINFO_FILENAME);

        $controllerInstance = new Controller($module, $controller);
        $meta = $controllerInstance->getMeta();

        if ($route = $meta->getRoute()) {
            $routers[$module][$controller] = ['route' => $route, 'params' => $meta->getParams()];
        }
    }
    $this->assign('routers', $routers);
};
