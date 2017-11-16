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
 * @SWG\Head(
 *   path="/api/pages/{pageId}",
 *   tags={"pages"},
 *   operationId="getPageById",
 *   summary="Find page by ID",
 *   @SWG\Parameter(
 *      name="pageId",
 *      in="path",
 *      type="integer",
 *      required=true,
 *      description="ID of page that needs to be fetched"
 *    ),
 *    @SWG\Response(response=200, description="Given page found"),
 *    @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=404, description="Page not found")
 * )
 *
 * @SWG\Get(
 *   path="/api/pages/{pageId}",
 *   tags={"pages"},
 *   operationId="getPageById",
 *   summary="Find page by ID",
 *   @SWG\Parameter(
 *      name="pageId",
 *      in="path",
 *      type="integer",
 *      required=true,
 *      description="ID of page that needs to be fetched"
 *    ),
 *    @SWG\Response(@SWG\Schema(ref="#/definitions/pages"), response=200, description="Given page found"),
 *    @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=404, description="Page not found")
 * )
 *
 * @SWG\Get(
 *     path="/api/pages/",
 *     tags={"pages"},
 *     method="GET",
 *     operationId="getPageCollection",
 *     summary="Collection of items",
 *     @SWG\Parameter(ref="#/parameters/offset"),
 *     @SWG\Parameter(ref="#/parameters/limit"),
 *     @SWG\Response(response=200, description="Collection present"),
 *     @SWG\Response(response=206, description="Collection present")
 * )
 *
 * @ accept JSON
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $rest = new Rest(Pages\Crud::getInstance());

    $rest
        ->head('system', 'rest/head')
        ->fields([
            'id',
            'title',
            'content',
            'keywords',
            'description'
        ])
    ;
    $rest
        ->get('system', 'rest/get')
        ->fields([
            'id',
            'title',
            'content',
            'keywords',
            'description'
        ])
    ;

    return $rest->run();
};
