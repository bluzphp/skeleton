<?php
/**
 * Annotations for swagger
 * 
 * @author   Anton Shevchuk
 * @created  14.11.2014 18:01
 */

/**
 * @SWG\Resource(resourcePath="/pages")
 * @SWG\Api(
 *   path="/pages/rest/{pageId}",
 *   @SWG\Operation(
 *      method="GET", nickname="getPageById",  type="array", items="$ref:Pages",
 *      summary="Find page by ID",
 *      notes="Returns a page model",
 *      @SWG\Parameter(
 *          name="pageId",
 *          description="ID of page that needs to be fetched",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="Given page found", responseModel="Pages"),
 *      @SWG\ResponseMessage(code=404, message="Page not found", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/pages/rest/",
 *   @SWG\Operation(
 *      method="GET", nickname="getPages",
 *      summary="Collection of Pages",
 *      notes="Not Implemented",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 */
