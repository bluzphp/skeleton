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
 *   path="/api/profile",
 *   tags={"api", "users"},
 *   operationId="profile",
 *   summary="Get profile of current user",
 *   @SWG\Parameter(
 *       name="token",
 *       in="query",
 *       description="token",
 *       required=true,
 *       type="string"
 *   ),
 *   @SWG\Response(
 *     response=200,
 *     description="Given user found",
 *     @SWG\Schema(ref="#/definitions/users")
 *   ),
 *   @SWG\Response(
 *     response=403,
 *     description="Forbidden",
 *     @SWG\Schema(ref="#/definitions/errorModel")
 *   ),
 *   @SWG\Response(
 *     response=404,
 *     description="User not found",
 *     @SWG\Schema(ref="#/definitions/errorModel")
 *   )
 * )
 *
 * @method GET
 * @privilege UserProfile
 *
 * @throws NotFoundException
 */
return function () {
    /**
     * @var Controller $this
     */
    $user = Users\Table::findRow($this->user()->id);
    if (!$user) {
        throw new NotFoundException('User not found');
    }
    $this->getData()->setFromArray($user->toArray());
};
