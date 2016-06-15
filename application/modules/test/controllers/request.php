<?php
/**
 * Request examples
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  25.02.14 18:01
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
            'Request Examples',
        ]
    );
};
