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
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @privilege Management
 *
 * @return array
 */
return function () {
    /**
     * @var Controller $this
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
    
    return [
        'countCol' => $countCol,
        'col' => $lnCol,
        'grid' => $grid
    ];
};
