<?php
/**
 * Grid of users
 *
 * @author   Anton Shevchuk
 * @created  02.08.12 18:39
 * @return closure
 */
namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Controller\Data;
use Bluz\Proxy\Db;
use Bluz\Proxy\Layout;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($data, $module, $controller) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Users')
        ]
    );

    $grid = new Users\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $data->roles = Db::fetchAll('SELECT * FROM acl_roles');

    $data->grid = $grid;
};
