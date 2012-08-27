<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Bluz;
use Application\Pages;

return
/**
 * @return \closure
 */
function() use ($view) {
    $view->grid = new Pages\Grid();
};
