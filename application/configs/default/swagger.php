<?php
/**
 * Annotations for swagger
 *
 * @link     http://swagger.io/
 * @author   Anton Shevchuk
 * @created  19.10.2015 11:16
 */

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     produces={"application/json"},
 *     consumes={"application/x-www-form-urlencoded"},
 *     @SWG\Info(
 *         version="1.1.0",
 *         title="Bluz PHP API",
 *         description="API of Bluz Skeleton application",
 *         @SWG\Contact(name="Bluz PHP Team"),
 *         @SWG\License(name="MIT")
 *     ),
 *     @SWG\Definition(
 *         definition="error",
 *         required={"code", "error"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32",
 *             example=404
 *         ),
 *         @SWG\Property(
 *             property="error",
 *             type="string",
 *             example="Not found"
 *         )
 *     ),
 *     @SWG\Parameter(
 *         name="Auth-Token",
 *         in="header",
 *         description="token",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Parameter(
 *         name="offset",
 *         in="query",
 *         description="Query offset",
 *         required=false,
 *         type="integer"
 *     ),
 *     @SWG\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Query limit",
 *         required=false,
 *         type="integer"
 *     )
 * )
 */
