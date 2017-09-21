<?php
/**
 * REST controller for GET method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */

namespace Application;

use Bluz\Http\StatusCode;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @accept HTML
 * @accept JSON
 * @method GET
 *
 * @param  \Bluz\Crud\Table $crud
 * @param  mixed            $primary
 *
 * @return array
 */
return function ($crud, $primary) {
    if (!empty($primary)) {
        // @throws NotFoundException
        return [$crud->readOne($primary)];
    }
    $params = Request::getParams();

    // setup default offset and limit - safe way
    $offset = Request::getParam('offset', 0);
    $limit = Request::getParam('limit', 10);

    if ($range = Request::getHeader('Range')) {
        list(, $offset, $last) = preg_split('/[-=]/', $range);
        // for better compatibility
        $limit = $last - $offset;
    }

    list($data, $total) = $crud->readSet($offset, $limit, $params);

    if (count($data) < $total) {
        Response::setStatusCode(StatusCode::PARTIAL_CONTENT);
        Response::setHeader(
            'Content-Range',
            'items ' . $offset . '-' . ($offset + count($data)) . '/' . $total
        );
    }

    return $data;
};
