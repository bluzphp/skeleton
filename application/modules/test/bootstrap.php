<?php
/**
 * Bootstrap of index module
 * 
 * @author   Anton Shevchuk
 * @created  07.07.11 18:03
 * @return closure
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function ($a) {
    /* @var Bluz\Application $this */
    $this->getLayout()->title("Test", \Bluz\View\View::POS_APPEND);
    return $a*2;
};
