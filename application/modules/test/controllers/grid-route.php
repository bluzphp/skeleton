<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Application\Test;
use Bluz\Proxy\Layout;

return
/**
 * Example of Grid with custom route
 *
 * @route /example/{$alias}
 * @param string $alias
 * @return \closure
 */
function ($alias) use ($view, $module, $controller) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Grid with Select',
        ]
    );
    $grid = new Test\SelectGrid();
    $grid->setModule($module);
    $grid->setController($controller);
    $grid->setParams(['alias'=>$alias]);

    $view->grid = $grid;

    return 'grid-sql.phtml';
};
