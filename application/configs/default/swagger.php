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
 *     basePath="/",
 *     schemes={"http"},
 *     produces={"application/json"},
 *     consumes={"application/x-www-form-urlencoded"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger documented API",
 *         description="Sample of Bluz API",
 *         @SWG\Contact(name="Bluz PHP Team"),
 *         @SWG\License(name="MIT")
 *     ),
 *     @SWG\Definition(
 *         definition="errorModel",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
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
