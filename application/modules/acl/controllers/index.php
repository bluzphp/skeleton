<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

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
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('ACL')
        ]
    );

    $set = array();
    foreach (new \GlobIterator(PATH_APPLICATION . '/modules/*/controllers/*.php') as $file) {
        $module = pathinfo(dirname(dirname($file->getPathname())), PATHINFO_FILENAME);
        $data = $this->reflection($file->getPathname());
        if (isset($data['privilege'])) {
            if (!isset($set[$module])) {
                $set[$module] = array();
            }

            if (!in_array($data['privilege'], $set[$module])) {
                $set[$module][] = $data['privilege'];
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
