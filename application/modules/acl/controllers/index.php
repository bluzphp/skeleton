<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Controller\Data;
use Bluz\Proxy\Layout;

return
/**
 * @privilege View
 *
 * @return void
 */
function () use ($data) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('ACL')
        ]
    );

    $set = array();
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = $file->getPathInfo()->getPathInfo()->getBasename();
        $controller = $file->getBasename('.php');

        $controllerInstance = new Controller($module, $controller);
        $reflection = $controllerInstance->getReflection();
        
        if ($privilege = $reflection->getPrivilege()) {
            if (!isset($set[$module])) {
                $set[$module] = array();
            }

            if (!in_array($privilege, $set[$module])) {
                $set[$module][] = $privilege;
            }
        }
    }

    $data->set = $set;
    $privilegesRowset = Privileges\Table::getInstance()->getPrivileges();
    $privileges = array();

    foreach ($privilegesRowset as $privilege) {
        if (!isset($privileges[$privilege->roleId])) {
            $privileges[$privilege->roleId] = array();
        }
        if (!isset($privileges[$privilege->roleId][$privilege->module])) {
            $privileges[$privilege->roleId][$privilege->module] = array();
        }
        $privileges[$privilege->roleId][$privilege->module][] = $privilege->privilege;
    }
    $data->privileges = $privileges;
    $data->roles = Roles\Table::getInstance()->getRoles();
};
