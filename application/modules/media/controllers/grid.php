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

use Bluz\Controller\Controller;
use Bluz\Controller\Data;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($data) {
    /**
     * @var Controller $this
     * @var Data $data
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
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
    $data->countCol = $countCol;
    $data->col = $lnCol;
    $data->grid = $grid;
};
