<?php
/**
 * Example of route with many params
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
 * @route /{$a}-{$b}-{$c}/
 * @param int $a
 * @param float $b
 * @param string $c
 * @return false
 */
return function ($a, $b, $c) {
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
 * @route /{\$a}-{\$b}-{\$c}/
 * @param int \$a
 * @param float \$b
 * @param string \$c
 * @return closure
 */
</pre>
CODE;
    var_dump(['$a'=>$a, '$b'=>$b, '$c'=>$c]);
    return false;
};
