<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */
namespace Application;

use Application\Users;
use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @privilege EditPassword
 *
 * @param $password
 * @param $new_password
 * @param $new_password2
 * @return void
 * @throws NotFoundException
 */
return function ($password, $new_password, $new_password2) {
    /**
     * @var Controller $this
     */
    // change layout
    $this->useLayout('small.phtml');

    $userId = $this->user()->id;

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($userId);

    if (!$user) {
        throw new NotFoundException('User not found');
    }

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

            $authTable = Auth\Table::getInstance();

            // password check
            $authTable->checkEquals($user->login, $password);
            // create new Auth record
            $authTable->generateEquals($user, $new_password);

            Messages::addSuccess("The password was updated successfully");

            // try back to index
            Response::redirectTo('users', 'profile');
        } catch (BadRequestException $e) {
            Messages::addError($e->getMessage());
        } catch (AuthException $e) {
            Messages::addError($e->getMessage());
        }
    }
};
