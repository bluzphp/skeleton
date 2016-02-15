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
use Bluz\Proxy\Router;

return
/**
 * @route /test/route-with-other-params/{$alias}(.*)
 * @param string $alias
 */
function ($alias) use ($module, $controller, $view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    var_dump($alias);
    var_dump(Router::getParams());
    var_dump(Router::getRawParams());
    var_dump(Request::getParam('id'));
    var_dump(Request::getParam('alias'));
    var_dump(Request::getParam('PHPSESSID'));
    return false;
};
