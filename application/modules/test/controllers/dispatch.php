<?php
/**
 * Dispatch other controllers
 *
 * @author   Anton Shevchuk
 * @created  23.08.12 13:14
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
/**
 * @return void
 */
function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Dispatch',
        ]
    );
};
