<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */
namespace Bluz;
return
/**
 * @return closure
 */
function() use ($app, $bootstrap, $request, $view) {
    /**
     * @var \Bluz\Application $app
     */
    $acl = array();
    $reflection = array();
    // $controllers = glob(PATH_APPLICATION . '/modules/*/controllers/*.php');
    $modules = glob(PATH_APPLICATION . '/modules/*');
    foreach ($modules as $module) {
        $moduleName = substr($module, strrpos($module, '/')+1);
        $reflection[$moduleName] = array();
        $controllers = glob($module . '/controllers/*.php');
        foreach ($controllers as $controller) {
            $controllerName = substr(substr($controller, strrpos($controller, '/')+1), 0, -4);
            $closure = require $controller;
            $closureReflection = $app->reflection($moduleName, $controllerName, $closure);
            $reflection[$moduleName][$controllerName] = $closureReflection;
            if (isset($closureReflection['acl'])) {
                $acl[] = $closureReflection['acl'];
            }
        }
    }
    $view->acl = array_unique($acl);
    $view->reflection = $reflection;
    $view->groups = $app->getDb()->fetchObjects(
        'SELECT * FROM acl_roles WHERE `type` = "group"',
        array(),
        'Application\Groups\Row'
    );
};