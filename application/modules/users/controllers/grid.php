<?php
/**
 * Grid of users
 *
 * @author   Anton Shevchuk
 * @created  02.08.12 18:39
 * @return closure
 */
namespace Application;

use Bluz;
use Application\Users;
use Application\Users\Grid;

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
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Users')
        ]
    );

    $grid = new Users\Grid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->roles = $this->getDb()->fetchAll('SELECT * FROM acl_roles');

    $view->grid = $grid;
};
