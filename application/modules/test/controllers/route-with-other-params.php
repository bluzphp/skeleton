<?php
/**
 * 
 *
 * @category Application
 *
 * @author   dark
 * @created  18.12.13 18:39
 */
namespace Application;

use Bluz\Proxy\Request;

return
/**
 * @route /test/route-with-other-params/{$alias}(.*)
 */
function ($alias) use ($module, $controller, $view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    var_dump($alias);
    var_dump(Request::getParams());
    var_dump(Request::getAllParams());
    var_dump(Request::getRawParams());
    return false;
};
