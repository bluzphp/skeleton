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

use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;

return
/**
 * @param bool $sex
 * @param string $car
 * @param bool $remember
 * @return \closure
 */
function ($sex = false, $car = 'none', $remember = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'View Form Helpers',
        ]
    );
    /**
     * @var Bootstrap $this
     */
    $view->sex = $sex;
    $view->car = $car;
    $view->remember = $remember;

    if (Request::isPost()) {
        $view->params = Request::getAllParams();
    }
};
