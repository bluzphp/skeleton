<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */

namespace Application;

use Application\Users;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Controller\Controller;

/**
 * @SWG\Get(
 *   path="/api/users/{id}",
 *   tags={"users"},
 *   operationId="profileById",
 *   summary="Get profile by user id",
 *   @SWG\Parameter(ref="#/parameters/Auth-Token"),
 *   @SWG\Parameter(
 *     name="id",
 *     in="path",
 *     type="integer",
 *     required=true,
 *     description="User UID"
 *   ),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/users"), response=200, description="Given user found"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=403, description="Forbidden"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=404, description="User not found")
 * )
 *
 * @accept JSON
 * @method GET
 * @privilege Users/Id
 * @route  /api/users/{$id}
 * @param  integer $id
 * @throws NotFoundException
 */
return function ($id) {
    /**
     * @var Controller $this
     */
    $user = Users\Table::findRow($id);
    if (!$user) {
        throw new NotFoundException('User not found'.$id);
    }
    $this->getData()->setFromArray($user->toArray());
};
