<?php
/**
 * Router with other params
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  18.12.13 18:39
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Request;

/**
 * @route /test/route-with-other-params/{$alias}(.*)
 * @param string $alias
 * @return false
 */
return function ($alias) {
    /**
     * @var Controller $this
     */
    var_dump($alias);
    var_dump(Request::getParams());
    var_dump(Request::getParam('id'));
    var_dump(Request::getParam('alias'));
    var_dump(Request::getParam('PHPSESSID'));
    return false;
};
