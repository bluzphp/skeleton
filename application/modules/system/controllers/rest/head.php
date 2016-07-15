<?php
/**
 * REST controller for HEAD method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @accept JSON
 * @method HEAD
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed $primary
 * @return void
 */
return function ($crud, $primary) {
    if (!empty($primary)) {
        // @throws NotFoundException
        $result = [$crud->readOne($primary)];
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
        $result = $crud->readSet($offset, $limit, $params);
    }

    $size = strlen(json_encode($result));

    Response::addHeader('Content-Length', $size);
};
