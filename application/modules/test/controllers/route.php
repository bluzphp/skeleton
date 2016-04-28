<?php
/**
 * Route examples
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Routers Examples',
        ]
    );
};
