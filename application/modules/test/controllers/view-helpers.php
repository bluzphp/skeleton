<?php
/**
 * Demo of View helpers
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 16:12
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;

return
/**
 * @param bool $sex
 * @param string $car
 * @param bool $remember
 * @return \closure
 */
function ($sex = false, $car = 'none', $remember = false) {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'View Form Helpers',
        ]
    );
    /**
     * @var Controller $this
     */
    $this->assign('sex', $sex);
    $this->assign('car', $car);
    $this->assign('remember', $remember);

    if (Request::isPost()) {
        $this->assign('params', Request::getAllParams());
    }
};
