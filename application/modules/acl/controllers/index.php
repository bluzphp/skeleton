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
            __('Permissions')
        ]
    );

    $set = [];
    $path = PATH_APPLICATION . '/modules';
    $directoryIterator = new \DirectoryIterator($path);
    $modules = [];

    foreach ($directoryIterator as $directory) {
        if ($directory->isDot() || !$directory->isDir()) {
            continue;
        }
        $modules[] = $directory->getBasename();
    }

    sort($modules);

    foreach ($modules as $module) {
        $controllerPath = $path . '/' . $module . '/controllers/';
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

        if (!isset($set[$module])) {
            $set[$module] = [];
        }

        foreach ($filesIterator as $filePath => $fileInfo) {
            /* @var \SplFileInfo $fileInfo */
            if ($fileInfo->getExtension() !== 'php') {
                continue;
            }
            $controller = $fileInfo->getBasename('.php');
            if ($prefix = substr($fileInfo->getPath(), $controllerPathLength)) {
                $controller = $prefix . '/' . $controller;
            }
            $controllerInstance = new Controller($module, $controller);
            $meta = $controllerInstance->getMeta();


            if (($privilege = $meta->getPrivilege()) && !in_array($privilege, $set[$module], true)) {
                $set[$module][] = $privilege;
            }

            if ($acl = $meta->getAcl()) {
                array_push($set[$module], ...$acl);
            }
        }

        $set[$module] = array_unique($set[$module]);
    }
    $this->assign('set', $set);

    $privilegesRowset = Privileges\Table::getInstance()->getPrivileges();
    $privileges = [];

    foreach ($privilegesRowset as $privilege) {
        array_add($privileges, $privilege->roleId, $privilege->module, $privilege->privilege);
    }

    $this->assign('privileges', $privileges);
    $this->assign('roles', Roles\Table::getInstance()->getRoles());
};
