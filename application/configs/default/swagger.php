<?php

/**
 * Annotations for swagger
 *
 * @link     http://swagger.io/
 * @author   Anton Shevchuk
 * @created  19.10.2015 11:16
 */

/**
 * @OA\Info(
 *   version="2.0.0",
 *   title="Bluz PHP API",
 *   description="API of Bluz Skeleton application",
 *   @OA\Contact(name="Bluz PHP Team"),
 *   @OA\License(name="MIT")
 * )
 *
 * @OA\SecurityScheme(
 *   type="apiKey",
 *   in="header",
 *   securityScheme="api_key",
 *   name="Auth-Token"
 * )
 *
 * @OA\Schema(
 *   schema="error",
 *   required={"code", "message"},
 *   @OA\Property(
 *     property="code",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="message",
 *     type="string"
 *   )
 * ),
 *
 * @OA\Parameter(
 *   parameter="offset_in_query",
 *   name="offset",
 *   description="Query offset",
 *   @OA\Schema(
 *     type="integer",
 *     format="int64",
 *   ),
 *   in="query",
 *   required=false
 * )
 *
 * @OA\Parameter(
 *   parameter="limit_in_query",
 *   name="limit",
 *   description="Query limit",
 *   @OA\Schema(
 *     type="integer",
 *     format="int64",
 *   ),
 *   in="query",
 *   required=false
 * )
 */
