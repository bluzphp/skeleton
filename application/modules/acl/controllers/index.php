<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @privilege View
 *
 * @return void
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('ACL')
        ]
    );

    $set = array();
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = pathinfo(dirname(dirname($file->getPathname())), PATHINFO_FILENAME);
        $reflection = $this->reflection($file->getPathname());
        if ($privilege = $reflection->getPrivilege()) {
            if (!isset($set[$module])) {
                $set[$module] = array();
            }

            if (!in_array($privilege, $set[$module])) {
                $set[$module][] = $privilege;
            }
        }
    }

    $view->set = $set;
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
    $view->privileges = $privileges;
    $view->roles = Roles\Table::getInstance()->getRoles();
};
