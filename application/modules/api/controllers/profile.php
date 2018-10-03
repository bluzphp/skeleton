<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */

namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;

/**
 * Get profile of current user
 *
 * @OA\Get(
 *   path="/api/profile",
 *   tags={"users"},
 *   operationId="profile",
 *   summary="Get profile of current user",
 *   security={
 *     {"api_key": {}}
 *   },
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/user"), response=200, description="Given user found"),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=403, description="Forbidden"),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=404, description="User not found")
 * )
 *
 * @accept JSON
 * @method GET
 * @privilege Users/Profile
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->getData()->setFromArray(
        $this->user()->toArray()
    );
};
