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
 * @return \closure
 */
function ($resource, $id, $relation, $relationId) {
    /**
     * @var Bootstrap $this
     */
    $this->useJson();

    Auth::clearIdentity();

    try {
        // authentication
        if ($token = $this->getRequest()->getParam('token')) {
            Table::getInstance()->authenticateToken($token);
        }
        Request::setRawParams([$id, $relation, $relationId]);
        return $this->dispatch('api', $resource);
    } catch (\Exception $e) {
        // process exceptions here
        $this->getResponse()->setStatusCode($e->getCode());
        return (object)['error' => $e->getMessage()];
    }
};
