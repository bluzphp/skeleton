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
 * @return \closure
 */
return
/**
 * List of custom routers
 */
function () use ($view) {
    $routers = array();
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = pathinfo(dirname(dirname($file->getPathname())), PATHINFO_FILENAME);
        $controller = pathinfo($file->getPathname(), PATHINFO_FILENAME);
        $data = $this->reflection($file->getPathname());
        if (isset($data['route'])) {
            if (!isset($routers[$module])) {
                $routers[$module] = array();
            }

            $routers[$module][$controller] = ['route' => $data['route'], 'params' => $data['types']];
        }
    }
    $view->routers = $routers;
};