<?php
/**
 * Example of static route
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;

/**
 * @route /static-route/
 * @route /another-route.html
 * @return false
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Routers Examples',
        ]
    );

    $uri = Request::getUri();
    $module = Request::getModule();
    $controller = Request::getController();
    echo <<<CODE
<h4>URL: $uri</h4>
<h4>Route: $module/$controller</h4>
<pre>
/**
 * @route /static-route/
 * @route /another-route.html
 * @return \closure
 */
</pre>
CODE;
    return false;
};
