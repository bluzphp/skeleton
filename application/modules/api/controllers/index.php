<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 12:29
 */

/**
 * @namespace
 */

namespace Application;

use Application\Auth\Provider;
use Bluz\Controller\Controller;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @accept JSON
 * @accept HTML
 *
 * @route  /api/{$resource}/{$id}/{$relation}/{$relationId}
 *
 * @param string $resource
 * @param string $id
 * @param string $relation
 * @param string $relationId
 *
 * @route  /api/{$resource}/{$id}/{$relation}
 *
 * @param string $resource
 * @param string $id
 * @param string $relation
 *
 * @route  /api/{$resource}/{$id}
 *
 * @param string $resource
 * @param string $id
 *
 * @route  /api/{$resource}
 *
 * @param string $resource
 *
 * @return mixed
 */
return function ($resource, $id, $relation, $relationId) {
    /**
     * @var Controller $this
     */
    $this->useJson();

    Auth::clearIdentity();

    try {
        // authentication by api token
        if ($token = Request::getParam('token')) {
            Provider\Token::authenticate($token);
        }

        $params = [];
        foreach ([$id, $relation, $relationId] as $param) {
            if (!is_null($param)) {
                $params[] = $param;
            }
        }

        return $this->dispatch('api', 'resources/' . $resource, $params);
    } catch (\Exception $e) {
        $code = $e->getCode() ?: \Bluz\Http\StatusCode::INTERNAL_SERVER_ERROR;
        // process exceptions here
        Response::setStatusCode($code);
        return [
            'code' => $e->getCode(),
            'error' => $e->getMessage()
        ];
    }
};
