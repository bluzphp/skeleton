<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Bluz;
use Application\Test;

return
/**
 * @return \closure
 */
function() use ($view) {
    $this->getLayout()->breadCrumbs([
        $view->ahref('Test', ['test', 'index']),
        'Grid with Array',
    ]);
    $view->grid = new Test\ArrayGrid();
};
