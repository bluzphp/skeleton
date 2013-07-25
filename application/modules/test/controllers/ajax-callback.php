<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    return ['callback'=>'alert'];
};
