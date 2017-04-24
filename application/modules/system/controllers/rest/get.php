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
 * @param  mixed $primary
 * @return array
 */
return function ($crud, $primary) {
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

        $total = 0;
        $result = $crud->readSet($offset, $limit, $params, $total);

        if (count($result) < $total) {
            Response::setStatusCode(StatusCode::PARTIAL_CONTENT);
            Response::setHeader(
                'Content-Range',
                'items ' . $offset . '-' . ($offset + count($result)) . '/' . $total
            );
        }
        
        return $result;
    }
};
