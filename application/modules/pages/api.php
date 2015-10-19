<?php
/**
 * Annotations for swagger
 *
 * @author   Anton Shevchuk
 * @created  14.11.2014 18:01
 */

/**
 * @SWG\Get(
 *   path="/pages/rest/{pageId}",
 *   tags={"page"},
 *   operationId="getPageById",
 *   summary="Find page by ID",
 *   @SWG\Parameter(
 *      name="pageId",
 *      in="path",
 *      type="integer",
 *      required=true,
 *      description="ID of page that needs to be fetched"
 *    ),
 *    @SWG\Response(response=200, description="Given page found", @SWG\Schema(ref="#/definitions/pages")),
 *    @SWG\Response(response=404, description="Page not found", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Get(
 *   path="/pages/rest/",
 *   tags={"page"},
 *   operationId="getPages",
 *   summary="Collection of Pages",
 *   @SWG\Response(response=501, description="Not Implemented", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 */
