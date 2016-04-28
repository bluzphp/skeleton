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

return
/**
 * @route /test/param/$
 * @route /test/param/{$a}/
 * @param string $a
 * @return \closure
 */
function ($a = 42) {
    /**
     * @var Controller $this
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
 * @route /test/param/{\$a}/
 * @param string \$a
 * @return closure
 */
</pre>
CODE;
    var_dump($a);
//    var_dump(Request::getParams());
    return false;
};
