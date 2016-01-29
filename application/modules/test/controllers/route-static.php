<?php
/**
 * Example of static route
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;

use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Proxy\Router;

return
/**
 * @route /static-route/
 * @route /another-route.html
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Routers Examples',
        ]
    );

    $uri = Request::getRequestUri();
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
