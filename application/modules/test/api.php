<?php
/**
 * Annotations for swagger
 *
 * @author   Anton Shevchuk
 * @created  14.11.2014 18:02
 */

/**
 * @SWG\Resource(resourcePath="/test")
 *
 * @SWG\Api(
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="HEAD", nickname="overviewCollection",
 *      summary="Check collection of items",
 *      notes="Returns a overview of collection",
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
 *   path="/test/rest-get/",
 *   @SWG\Operation(
 *      method="HEAD", nickname="overviewCollection",
 *      summary="Check collection of items",
 *      notes="Returns a overview of collection",
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
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="GET", nickname="getCollection", type="array", items="$ref:Test",
 *      summary="Collection of items",
 *      notes="Returns a collection, partial",
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
 *   path="/test/rest-get/",
 *   @SWG\Operation(
 *      method="GET", nickname="getCollection", type="array", items="$ref:Test",
 *      summary="Collection of items",
 *      notes="Returns a collection, partial",
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
 *      method="POST", nickname="create",
 *      summary="Create Item",
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
 *   path="/test/rest-post/",
 *   @SWG\Operation(
 *      method="POST", nickname="create",
 *      summary="Create Item",
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
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="PUT", nickname="updateCollection", summary="Try to update Collection",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest-put/",
 *   @SWG\Operation(
 *      method="PUT", nickname="updateCollection", summary="Try to update Collection",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 *
 * @SWG\Api(
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="DELETE", nickname="deleteCollection", summary="Try to delete Collection",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest-delete/",
 *   @SWG\Operation(
 *      method="DELETE", nickname="deleteCollection", summary="Try to delete Collection",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 *
 * @SWG\Api(
 *   path="/test/rest/",
 *   @SWG\Operation(
 *      method="OPTIONS", nickname="describeCollection",
 *      summary="Get allowed HTTP methods",
 *      @SWG\ResponseMessage(code=200, message="List of allowed HTTP methods")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest-options/",
 *   @SWG\Operation(
 *      method="OPTIONS", nickname="describeCollection",
 *      summary="Get allowed HTTP methods",
 *      @SWG\ResponseMessage(code=200, message="List of allowed HTTP methods")
 *   )
 * )
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="HEAD", nickname="overviewItem",
 *      summary="Check item by ID",
 *      notes="Returns a overview of item based on ID",
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
 *   path="/test/rest-get/{itemId}",
 *   @SWG\Operation(
 *      method="HEAD", nickname="overviewItem",
 *      summary="Check item by ID",
 *      notes="Returns a overview of item based on ID",
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
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="GET", nickname="getItem", type="array", items="$ref:Test",
 *      summary="Find item by ID",
 *      notes="Returns a item based on ID",
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
 *   path="/test/rest-get/{itemId}",
 *   @SWG\Operation(
 *      method="GET", nickname="getItem", type="array", items="$ref:Test",
 *      summary="Find item by ID",
 *      notes="Returns a item based on ID",
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
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="POST", nickname="createItem", summary="Try to create Item",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest-post/{itemId}",
 *   @SWG\Operation(
 *      method="POST", nickname="createItem", summary="Try to create Item",
 *      @SWG\ResponseMessage(code=501, message="Not Implemented", responseModel="ErrorModel")
 *   )
 * )
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="PUT", nickname="updateItem",
 *      summary="Update Item",
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
 *   path="/test/rest-put/{itemId}",
 *   @SWG\Operation(
 *      method="PUT", nickname="updateItem",
 *      summary="Update Item",
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
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="DELETE", nickname="deleteItem",
 *      summary="Delete Item",
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
 * @SWG\Api(
 *   path="/test/rest-delete/{itemId}",
 *   @SWG\Operation(
 *      method="DELETE", nickname="deleteItem",
 *      summary="Delete Item",
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
 *
 * @SWG\Api(
 *   path="/test/rest/{itemId}",
 *   @SWG\Operation(
 *      method="OPTIONS", nickname="describeItem",
 *      summary="Get allowed HTTP methods",
 *      @SWG\Parameter(
 *          name="itemId",
 *          description="ID of item",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="List of allowed HTTP methods")
 *   )
 * )
 * @SWG\Api(
 *   path="/test/rest-options/{itemId}",
 *   @SWG\Operation(
 *      method="OPTIONS", nickname="describeItem",
 *      summary="Get allowed HTTP methods",
 *      @SWG\Parameter(
 *          name="itemId",
 *          description="ID of item",
 *          paramType="path",
 *          required=true,
 *          allowMultiple=false,
 *          type="integer"
 *      ),
 *      @SWG\ResponseMessage(code=200, message="List of allowed HTTP methods")
 *   )
 * )
 */
