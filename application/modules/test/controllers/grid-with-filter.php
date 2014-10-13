<?php
/**
 * @author  Volkov Sergey
 */
namespace Application;

use Application\Test;
use Bluz\Proxy\Layout;

return
    /**
     * @return \closure
     */
    function () use ($view, $module, $controller) {
        /**
         * @var \Bluz\View\View $view
         */
        Layout::breadCrumbs(
            [
                $view->ahref('Test', ['test', 'index']),
                'Grid with Filter',
            ]
        );
        $grid = new Test\ArrayGrid();
        $grid->setModule($module);
        $grid->setController($controller);

        $view->grid = $grid;
    };
