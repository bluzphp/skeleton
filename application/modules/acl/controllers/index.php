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
use Bluz\Proxy\Layout;

/**
 * @privilege View
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('ACL')
        ]
    );

    $set = array();
    $path = PATH_APPLICATION . '/modules';
    $directoryIterator = new \DirectoryIterator($path);
    $modules = array();

    foreach ($directoryIterator as $directory) {
        if ($directory->isDot() || !$directory->isDir()) {
            continue;
        }
        $modules[] = $directory->getBasename();
    }

    sort($modules);

    foreach ($modules as $module) {
        $controllerPath = $path .'/'. $module .'/controllers/';
        $controllerPathLength = strlen($controllerPath);

        if (!is_dir($controllerPath)) {
            continue;
        }
        $filesIterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $controllerPath,
                \FilesystemIterator::KEY_AS_PATHNAME
                | \FilesystemIterator::CURRENT_AS_FILEINFO
                | \FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($filesIterator as $filePath => $fileInfo) {
            /* @var \SplFileInfo $fileInfo */
            $controller = $fileInfo->getBasename('.php');
            if ($prefix = substr($fileInfo->getPath(), $controllerPathLength)) {
                $controller = $prefix .'/'. $controller;
            }
            $controllerInstance = new Controller($module, $controller);
            $reflection = $controllerInstance->getReflection();

            if (!isset($set[$module])) {
                $set[$module] = array();
            }

            if ($privilege = $reflection->getPrivilege()) {
                if (!in_array($privilege, $set[$module])) {
                    $set[$module][] = $privilege;
                }
            }

            if ($acl = $reflection->getAcl()) {
                $set[$module] += $acl;
            }
        }
    }
    $this->assign('set', $set);
    
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
    
    $this->assign('privileges', $privileges);
    $this->assign('roles', Roles\Table::getInstance()->getRoles());
};
