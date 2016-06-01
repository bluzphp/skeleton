<?php
/**
 * Annotations for swagger
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  14.11.2014 18:02
 */

/**
 * @SWG\Get(
 *     path="/test/rest/",
 *     tags={"test"},
 *     method="GET",
 *     operationId="getCollection",
 *     summary="Collection of items",
 *     @SWG\Parameter(ref="#/parameters/offset"),
 *     @SWG\Parameter(ref="#/parameters/limit"),
 *     @SWG\Response(response=200, description="Collection present"),
 *     @SWG\Response(response=206, description="Collection present")
 * )
 *
 * @SWG\Post(
 *     path="/test/rest/",
 *     tags={"test"},
 *     operationId="create",
 *     summary="Create Item",
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Response(response=201, description="Item created, will return Location of created item"),
 *     @SWG\Response(response=400, description="Validation errors")
 * )
 *
 * @SWG\Put(
 *     path="/test/rest/",
 *     tags={"test"},
 *     operationId="updateCollection",
 *     summary="Try to update Collection",
 *     @SWG\Response(response=501, description="Not Implemented", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Delete(
 *     path="/test/rest/",
 *     tags={"test"},
 *     operationId="deleteCollection",
 *     summary="Try to delete Collection",
 *     @SWG\Response(response=400, description="Not Found", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 */

/**
 * @SWG\Get(
 *     path="/test/rest/{itemId}",
 *     tags={"test"},
 *     operationId="get",
 *     summary="Find item by ID",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be fetched",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Response(response=200, description="Given item found", @SWG\Schema(ref="#/definitions/test")),
 *     @SWG\Response(response=404, description="Item not found", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Post(
 *     path="/test/rest/{itemId}",
 *     tags={"test"},
 *     operationId="create",
 *     summary="Try to create Item",
 *     @SWG\Response(response=501, description="Not Implemented", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Put(
 *     path="/test/rest/{itemId}",
 *     tags={"test"},
 *     operationId="update",
 *     summary="Update Item",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be updated",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name",
 *         required=false,
 *         type="string",
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email",
 *         required=false,
 *         type="string",
 *     ),
 *     @SWG\Response(response=404, description="Item not found", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Delete(
 *     path="/test/rest/{itemId}",
 *     tags={"test"},
 *     operationId="delete",
 *     summary="Delete Item",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be removed",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Response(response=204, description="Item removed"),
 *     @SWG\Response(response=404, description="Item not found")
 * )
 */

/**
 * @SWG\Get(
 *     path="/test/rest-get/",
 *     tags={"test get"},
 *     operationId="getCollection",
 *     summary="Collection of items",
 *     @SWG\Parameter(ref="#/parameters/offset"),
 *     @SWG\Parameter(ref="#/parameters/limit"),
 *     @SWG\Response(response=206, description="Collection present")
 * )
 *
 * @SWG\Get(
 *     path="/test/rest-get/{itemId}",
 *     tags={"test get"},
 *     operationId="get",
 *     summary="Find item by ID",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be fetched",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Response(response=200, description="Given item found", @SWG\Schema(ref="#/definitions/test")),
 *     @SWG\Response(response=404, description="Item not found", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 */

/**
 * @SWG\Post(
 *     path="/test/rest-post/",
 *     tags={"test post"},
 *     operationId="create",
 *     summary="Create Item",
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Response(response=201, description="Item created, will return Location of created item"),
 *     @SWG\Response(response=400, description="Validation errors")
 * )
 *
 * @SWG\Post(
 *     path="/test/rest-post/{itemId}",
 *     tags={"test post"},
 *     operationId="create",
 *     summary="Try to create Item",
 *     @SWG\Response(response=501, description="Not Implemented", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 */

/**
 * @SWG\Put(
 *     path="/test/rest-put/",
 *     tags={"test put"},
 *     operationId="updateCollection",
 *     summary="Try to update Collection",
 *     @SWG\Response(response=400, description="Validation errors", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Put(
 *     path="/test/rest-put/{itemId}",
 *     tags={"test put"},
 *     operationId="update",
 *     summary="Update Item",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be updated",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name",
 *         required=false,
 *         type="string"
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email",
 *         required=false,
 *         type="string"
 *     ),
 *     @SWG\Response(response=200, description="Item updated"),
 *     @SWG\Response(response=304, description="Not modified"),
 *     @SWG\Response(response=400, description="Validation errors"),
 *     @SWG\Response(response=404, description="Item not found")
 * )
 */

/**
 * @SWG\Delete(
 *     path="/test/rest-delete/",
 *     tags={"test delete"},
 *     operationId="deleteCollection",
 *     summary="Try to delete Collection",
 *     @SWG\Response(response=501, description="Not Implemented", @SWG\Schema(ref="#/definitions/errorModel"))
 * )
 *
 * @SWG\Delete(
 *     path="/test/rest-delete/{itemId}",
 *     tags={"test delete"},
 *     operationId="delete",
 *     summary="Delete Item",
 *     @SWG\Parameter(
 *         name="itemId",
 *         in="path",
 *         description="ID of item that needs to be removed",
 *         required=true,
 *         type="integer"
 *     ),
 *     @SWG\Response(response=204, description="Item removed"),
 *     @SWG\Response(response=404, description="Item not found")
 * )
 */
