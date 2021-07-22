<?php

/**
 * Public REST for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */

namespace Application;

use Application\Pages;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Rest;

/**
 * Get all pages or just one by ID
 * For everyone
 *
 * @OA\Head(
 *   path="/api/pages/{pageId}",
 *   tags={"pages"},
 *   operationId="getPageById",
 *   summary="Find page by ID",
 *   @OA\Parameter(
 *      name="pageId",
 *      in="path",
 *      required=true,
 *      description="ID of page that needs to be fetched",
 *      @OA\Schema(type="integer")
 *    ),
 *    @OA\Response(response=200, description="Given page found"),
 *    @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=404, description="Page not found")
 * )
 *
 * @OA\Get(
 *   path="/api/pages/{pageId}",
 *   tags={"pages"},
 *   operationId="getPageById",
 *   summary="Find page by ID",
 *   @OA\Parameter(
 *      name="pageId",
 *      in="path",
 *      required=true,
 *      description="ID of page that needs to be fetched",
 *      @OA\Schema(type="integer")
 *    ),
 *    @OA\Response(
 *      @OA\JsonContent(ref="#/components/schemas/page"),
 *      response=200,
 *      description="Given page found"
 *   ),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=404, description="Page not found")
 * )
 *
 * @OA\Get(
 *     path="/api/pages/",
 *     tags={"pages"},
 *     method="GET",
 *     operationId="getPageCollection",
 *     summary="Collection of items",
 *     @OA\Parameter(ref="#/components/parameters/offset_in_query"),
 *     @OA\Parameter(ref="#/components/parameters/limit_in_query"),
 *     @OA\Response(
 *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/page")),
 *          response=200,
 *          description="Collection"
 *     ),
 *     @OA\Response(
 *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/page")),
 *          response=206,
 *          description="Collection (partial)"
 *     )
 * )
 *
 * @accept JSON
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $rest = new Rest(Pages\Crud::getInstance());

    $rest
        ->head('system', 'rest/head')
        ->fields(
            [
                'id',
                'title',
                'content',
                'keywords',
                'description'
            ]
        );
    $rest
        ->get('system', 'rest/get')
        ->fields(
            [
                'id',
                'title',
                'content',
                'keywords',
                'description'
            ]
        );

    return $rest->run();
};
