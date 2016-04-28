<?php
/**
 * @author  Volkov Sergey
 */
namespace Application;

use Application\Test;
use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
    /**
     * @return \closure
     */
    function () {
        /**
         * @var Controller $this
         */
        Layout::breadCrumbs(
            [
                Layout::ahref('Test', ['test', 'index']),
                'Grid with Filter',
            ]
        );
        $grid = new Test\ArrayGrid();
        $grid->setModule($this->module);
        $grid->setController($this->controller);

        $this->assign('grid', $grid);
    };
