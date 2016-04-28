<?php
/**
 * Example of backbone usage
 *
 * @category Application
 *
 * @author   dark
 * @created  13.08.13 17:16
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
            'Backbone',
        ]
    );
};
