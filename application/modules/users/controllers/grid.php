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
use Bluz\Proxy\Db;
use Bluz\Proxy\Layout;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($view, $module, $controller) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Users')
        ]
    );

    $grid = new Users\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->roles = Db::fetchAll('SELECT * FROM acl_roles');

    $view->grid = $grid;
};
