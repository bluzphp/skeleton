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
 *   description="Pages",
 *   @SWG\Operation(
 *      method="GET", summary="Find page by ID", notes="Returns a page based on ID",
 *      type="Pages", nickname="getPageById", consumes="['application/xml','application/json']",
 *      @SWG\Parameter(
 *          name="pageId",
 *          description="ID of page that needs to be fetched",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="string"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="Given page found", responseModel="Pages"),
 *      @SWG\ResponseMessage(code=404, message="Page not found", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/pages/rest/",
 *   description="Pages",
 *   @SWG\Operation(
 *      method="GET", summary="Collection of Pages", notes="Not Implemented", nickname="getPages",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 */
