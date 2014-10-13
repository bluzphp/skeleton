<?php
/**
 * Bootstrap of index module
 * 
 * @author   Anton Shevchuk
 * @created  07.07.11 18:03
 * @return closure
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function ($a) {
    Layout::title("Test", Layout::POS_APPEND);
    return $a*2;
};
