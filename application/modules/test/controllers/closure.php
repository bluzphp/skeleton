<?php
/**
 * Disable view, like for backbone.js
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;
use Bluz;
return
/**
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     */
//    $this->useLayout(false);
    return function() {
        echo "Closure is back";
    };
};
