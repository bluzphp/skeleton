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
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="HEAD", summary="Check item by ID", notes="Returns a overview of item based on ID",
 *      type="Test", nickname="getItemOverview",
 *      @SWG\Parameter(
 *          name="itemId",
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
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="HEAD", summary="Collection of items", notes="Returns a overview of collection",
 *      type="Test", nickname="getItemCollection",
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
 *      @SWG\ResponseMessage(code=200, message="Collection present")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="GET", summary="Find item by ID", notes="Returns a item based on ID",
 *      type="Test", nickname="getItemById",
 *      @SWG\Parameter(
 *          name="itemId",
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
 *   @SWG\Operation(
 *      method="GET", summary="Collection of items", notes="Returns a collection, partial",
 *      type="Test", nickname="getItemCollection",
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
 * @SWG\Api(
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="POST", summary="Create Item",
 *      type="Test", nickname="createItem",
 *      @SWG\Parameter(
 *          name="name",
 *          description="Name",
 *          paramType="form",
 *          required=true,
 *          allowMultiple=false,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="email",
 *          description="Email",
 *          paramType="form",
 *          required=true,
 *          allowMultiple=false,
 *          type="string"
 *      ),
 *      @SWG\ResponseMessage(code=201, message="Item created, will return Location of created item"),
 *      @SWG\ResponseMessage(code=400, message="Validation errors")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="PUT", summary="Update Item",
 *      type="Test", nickname="updateItem",
 *      @SWG\Parameter(
 *          name="itemId",
 *          description="ID of item that needs to be updated",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\Parameter(
 *          name="name",
 *          description="Name",
 *          paramType="form",
 *          required=false,
 *          allowMultiple=false,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="email",
 *          description="Email",
 *          paramType="form",
 *          required=false,
 *          allowMultiple=false,
 *          type="string"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="Item updated"),
 *      @SWG\ResponseMessage(code=304, message="Not modified"),
 *      @SWG\ResponseMessage(code=400, message="Validation errors"),
 *      @SWG\ResponseMessage(code=404, message="Item not found")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="DELETE", summary="Delete Item",
 *      type="Test", nickname="deleteItem",
 *      @SWG\Parameter(
 *          name="itemId",
 *          description="ID of item that needs to be removed",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=204, message="Item removed"),
 *      @SWG\ResponseMessage(code=404, message="Item not found")
 *   )
 * )
 */
