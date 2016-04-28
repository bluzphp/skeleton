<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 12:29
 */

/**
 * @namespace
 */
namespace Application;

use Application\Auth\Table;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Request;

return
/**
 * @route /api/{$resource}/{$id}/{$relation}/{$relationId}
 * @param string $resource
 * @param string $id
 * @param string $relation
 * @param string $relationId
 *
 * @route /api/{$resource}/{$id}/{$relation}
 * @param string $resource
 * @param string $id
 * @param string $relation
 *
 * @route /api/{$resource}/{$id}
 * @param string $resource
 * @param string $id
 *
 * @route /api/{$resource}
 * @param string $resource
 *
 * @accept JSON
 * @accept HTML
 * @return \closure
 */
function ($resource, $id, $relation, $relationId) {
    /**
     * @var Controller $this
     */
    $this->useJson();

    Auth::clearIdentity();

    try {
        // authentication
        if ($token = Request::getParam('token')) {
            Table::getInstance()->authenticateToken($token);
        }

        $params = [];
        foreach ([$id, $relation, $relationId] as $param) {
            if (!is_null($param)) {
                $params[] = $param;
            }
        }

        $request = Request::getInstance();
        $request = $request->withQueryParams($params);
        Request::setInstance($request);

        return $this->dispatch('api', $resource);
    } catch (\Exception $e) {
        // process exceptions here
        $this->getResponse()->setStatusCode($e->getCode());
        return (object)['error' => $e->getMessage()];
    }
};
