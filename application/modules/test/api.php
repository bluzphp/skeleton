<?php
/**
 * Annotations for swagger
 * 
 * @author   Anton Shevchuk
 * @created  14.11.2014 18:02
 */

/**
 * @SWG\Resource(resourcePath="/test")
 * @SWG\Api(
 *   path="/test/rest/{testId}",
 *   description="Test",
 *   @SWG\Operation(
 *      method="HEAD", summary="Find item by ID", notes="Returns a overview of item based on ID",
 *      type="Test", nickname="getTestOverviewById",
 *      @SWG\Parameter(
 *          name="testId",
 *          description="ID of item that needs to be fetched",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="Given item found"),
 *      @SWG\ResponseMessage(code=404, message="Item not found")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest/{testId}",
 *   description="Test",
 *   @SWG\Operation(
 *      method="GET", summary="Find item by ID", notes="Returns a item based on ID",
 *      type="Test", nickname="getTestById",
 *      @SWG\Parameter(
 *          name="testId",
 *          description="ID of item that needs to be fetched",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="Given item found", responseModel="Test"),
 *      @SWG\ResponseMessage(code=404, message="Item not found", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest/",
 *   description="Test",
 *   @SWG\Operation(
 *      method="GET", summary="Collection of items", notes="Returns a collection, partial",
 *      type="Test", nickname="getTests",
 *      @SWG\Parameter(
 *          name="offset",
 *          description="Query offset",
 *          paramType="query",
 *          required=false,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\Parameter(
 *          name="limit",
 *          description="Query limit",
 *          paramType="query",
 *          required=false,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=206, message="Collection present")
 *   )
 * )
 */
