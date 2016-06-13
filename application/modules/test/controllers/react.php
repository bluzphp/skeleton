<?php
/**
 * Example of ReactJS
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.05.16 16:27
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @privilege Read
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'React',
        ]
    );
};
