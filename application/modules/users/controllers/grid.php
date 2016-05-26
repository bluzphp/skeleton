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
use Bluz\Proxy\Db;
use Bluz\Proxy\Layout;

/**
 * @privilege Management
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Users')
        ]
    );

    $grid = new Users\Grid();
    $grid->setModule($this->module);
    $grid->setController($this->controller);
    
    $this->assign('roles', Db::fetchAll('SELECT * FROM acl_roles'));
    $this->assign('grid', $grid);
};
