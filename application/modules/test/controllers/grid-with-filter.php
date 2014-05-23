<?php
/**
 * @author  Volkov Sergey
 */
namespace Application;

use Bluz;
use Application\Test;

return
    /**
     * @return \closure
     */
    function () use ($view, $module, $controller) {
        /**
         * @var \Bluz\View\View $view
         */
        app()->getLayout()->breadCrumbs(
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
