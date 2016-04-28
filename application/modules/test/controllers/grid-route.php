<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Application\Test;
use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
/**
 * Example of Grid with custom route
 *
 * @route /example/{$alias}
 * @param string $alias
 * @return \closure
 */
function ($alias) {
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
    $grid->setParams(['alias'=>$alias]);

    $this->assign('grid', $grid);

    return 'grid-sql.phtml';
};
