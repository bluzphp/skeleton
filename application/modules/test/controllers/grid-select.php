<?php
/**
 * Example of grid based on Select
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
 * @return string
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Grid with Select',
        ]
    );

    $grid = new Test\SelectGrid();
    $grid->setModule($this->module);
    $grid->setController($this->controller);

    $this->assign('grid', $grid);

    return 'grid-sql.phtml';
};
