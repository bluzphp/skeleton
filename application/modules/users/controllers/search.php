<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */

namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Proxy\Request;

/**
 * @ accept    JSON
 * @method    GET
 * @privilege Management
 */
return function () {
    /**
     * @var Controller $this
     */
    $params = Request::getParams();

    $params = array_intersect_key($params, Users\Table::getMeta());

    if (empty($params)) {
        return [];
    }
    return Users\Table::findWhere($params);
};
