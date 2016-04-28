<?php
/**
 * Example of REST controller for GET method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Application\Test;
use Bluz\Controller;
use Bluz\Proxy\Request;

/**
 * @param $primary
 * @param $data
 * @param Test\Crud $crud
 * @return array
 */
return
/**
 * @accept HTML
 * @accept JSON
 * @method HEAD
 * @method GET
 */
function ($primary, $data, $crud) {
    if (!empty($primary)) {
        // @throws NotFoundException
        return [$crud->readOne($primary)];
    } else {

        $params = Request::getParams();

        // setup default offset and limit - safe way
        $offset = Request::getParam('offset', 0);
        $limit = Request::getParam('limit', 10);

        if ($range = Request::getHeader('Range')) {
            list(, $offset, $last) = preg_split('/[-=]/', $range);
            // for better compatibility
            $limit = $last - $offset;
        }
        return $crud->readSet($offset, $limit, $params);
    }
};
