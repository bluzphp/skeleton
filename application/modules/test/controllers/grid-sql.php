<?php
/**
 * Example of grid based on SQL
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Application\Test;
use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Grid with SQL',
        ]
    );
    $grid = new Test\SqlGrid();
    $grid->setModule($this->module);
    $grid->setController($this->controller);
    // just example of same custom param for build URL
    $grid->setParams(['id'=>5]);

    $this->assign('grid', $grid);
};
