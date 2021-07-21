<?php

/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */

namespace Application;

use Application\Auth\Provider;
use Application\Users;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\BadRequestException;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @privilege EditPassword
 *
 * @param $password
 * @param $new_password
 * @param $new_password2
 *
 * @return void
 * @throws NotFoundException
 */
return function ($password, $new_password, $new_password2) {
    /**
     * @var Controller $this
     */
    /**
     * @var Users\Row $user
     */
    $user = $this->user();
    $this->assign('user', $user);

    if (Request::isPost()) {
        // process form
        try {
            if (empty($password)) {
                throw new BadRequestException('Please input current password');
            }

            if (empty($new_password)) {
                throw new BadRequestException('Please input new password');
            }

            if (empty($new_password2)) {
                throw new BadRequestException('Please repeat new password');
            }

            if ($new_password !== $new_password2) {
                throw new BadRequestException('Please repeat correct new password');
            }

            // password check
            Provider\Equals::verify($user->login, $password);

            // create new Auth record
            Provider\Equals::create($user, $new_password);

            Messages::addSuccess('The password was updated successfully');

            // try back to index
            Response::redirectTo('users', 'profile');
        } catch (BadRequestException $e) {
            Messages::addError($e->getMessage());
        } catch (AuthException $e) {
            Messages::addError($e->getMessage());
        }
    }
};
