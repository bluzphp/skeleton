<?php
/**
 * Dispatch other controllers
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  23.08.12 13:14
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return void
 */
return function () {
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
