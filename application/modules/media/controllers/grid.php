<?php
/**
 * Grid of Media
 *
 * @author   Anton Shevchuk
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

return
/**
 * @privilege Management
 * @return \closure
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
            __('Media')
        ]
    );
    $grid = new Media\Grid();

    $countCol = Request::getParam('countCol');

    if ($countCol <> null) {
        Response::setCookie("countCol", $countCol, time() + 3600, '/');
    } else {
        $countCol = Request::getCookie('countCol', 4);
    }

    $lnCol = (integer)(12 / $countCol);
    $view->countCol = $countCol;
    $view->col = $lnCol;
    $view->grid = $grid;
};
