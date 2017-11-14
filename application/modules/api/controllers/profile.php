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
 * @SWG\Get(
 *   path="/api/profile",
 *   tags={"users"},
 *   operationId="profile",
 *   summary="Get profile of current user",
 *   @SWG\Parameter(ref="#/parameters/Auth-Token"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/users"), response=200, description="Given user found"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=403, description="Forbidden"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=404, description="User not found")
 * )
 *
 * @accept JSON
 * @method GET
 * @privilege Users/Profile
 *
 * @throws NotFoundException
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->getData()->setFromArray(
        $this->user()->toArray()
    );
};
