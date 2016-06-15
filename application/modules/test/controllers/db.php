<?php
/**
 * Example of DB usage
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
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
            'Basic DB operations',
        ]
    );
    // all examples inside view
};
