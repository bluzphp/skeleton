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
    $this->getLayout()->setTemplate('dashboard.phtml');
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Dashboard', ['dashboard', 'index']),
            __('Media')
        ]
    );
    $grid = new Media\Grid();

    $request = $this->getRequest();
    $countCol = $request->getParam('countCol');

    if ($countCol <> null) {
        setcookie("countCol", $countCol, time() + 3600, '/');
    } else {
        $countCol = $request->getCookie('countCol', 4);
    }

    $lnCol = (integer)(12 / $countCol);
    $view->countCol = $countCol;
    $view->col = $lnCol;
    $view->grid = $grid;
};
