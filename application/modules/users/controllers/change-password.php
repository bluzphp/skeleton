<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */
namespace Application;

use Application\Users;
use Bluz\Auth\AuthException;
use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotFoundException;

return
/**
 * @privilege EditPassword
 * @return \closure
 */
function ($password, $new_password, $new_password2) use ($view) {
    /**
     * @var Bootstrap $this
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

    if ($this->getRequest()->isPost()) {
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

            $this->getMessages()->addSuccess("The password was updated successfully");

            // try back to index
            $this->redirectTo('users', 'profile');
        } catch (BadRequestException $e) {
            $this->getMessages()->addError($e->getMessage());
        } catch (AuthException $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
};
