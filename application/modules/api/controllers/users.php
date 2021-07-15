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
 * @OA\Get(
 *   path="/api/users/{id}",
 *   tags={"users"},
 *   operationId="profileById",
 *   summary="Get profile by user id",
 *   security={
 *     {"api_key": {}}
 *   },
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="User UID",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/user"), response=200, description="Given user found"),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=403, description="Forbidden"),
 *   @OA\Response(@OA\JsonContent(ref="#/components/schemas/error"), response=404, description="User not found")
 * )
 *
 * @accept JSON
 * @method GET
 * @privilege Users/Id
 * @route  /api/users/{$id}
 * @param integer $id
 */
return function ($id) {
    /**
     * @var Controller $this
     */
    $user = Users\Table::findRow($id);
    if (!$user) {
        throw new NotFoundException('User not found' . $id);
    }
    $this->getData()->setFromArray($user->toArray());
};
