<?php

/**
 * REST controller for Events model
 *
 * @author   dev
 * @created  2021-12-13 22:00:32
 */

namespace Application;

use Application\Events;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Rest;

/**
 *
 * @OA\Get(
 *   path="/api/events/{eventId}",
 *   tags={"events"},
 *   operationId="getEventById",
 *   summary="Find event by id",
 *   security={
 *     {"api_key": {}}
 *   },
 *   @OA\Parameter(
 *     name="eventId",
 *     in="path",
 *     required=true,
 *     description="Id of event",
 *     @OA\Schema(type="string")
 *   ),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/event"), response=200, description="Given event found"),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=404, description="Event not found")
 * )
 *
 * @OA\Get(
 *   path="/api/events/",
 *   tags={"events"},
 *   method="GET",
 *   operationId="getEventsCollection",
 *   summary="Collection of events",
 *   security={
 *     {"api_key": {}}
 *   },
 *   @OA\Parameter(ref="#/components/parameters/offset_in_query"),
 *   @OA\Parameter(ref="#/components/parameters/limit_in_query"),
 *   @OA\Response(
 *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/event")),
 *     response=200,
 *     description="Collection"
 *   ),
 *   @OA\Response(
 *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/event")),
 *     response=206,
 *     description="Collection (partial)"
 *   )
 * )
 *
 * @accept JSON
 *
 * @acl Events/Read
 * @acl Events/Edit
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $rest = new Rest(Events\Crud::getInstance());

    $rest->get('system', 'rest/get')->acl('Events/Read');
    $rest->post('system', 'rest/post')->acl('Events/Edit');
    $rest->put('system', 'rest/put')->acl('Events/Edit');
    $rest->patch('system', 'rest/put')->acl('Events/Edit');
    $rest->delete('system', 'rest/delete')->acl('Events/Edit');

    return $rest->run();
};
