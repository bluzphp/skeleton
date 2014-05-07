<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */
namespace Application;

use Application\Users;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Auth\AuthException;

return
/**
 * @privilege EditPassword
 * @return \closure
 */
function ($password, $new_password, $new_password2) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */
    // change layout
    $this->useLayout('small.phtml');

    $userId = $this->getAuth()->getIdentity()->id;

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
                throw new Exception('Please input current password');
            }

            if (empty($new_password)) {
                throw new Exception('Please input new password');
            }

            if (empty($new_password2)) {
                throw new Exception('Please repeat new password');
            }

            $authTable = Auth\Table::getInstance();

            // password check
            $authTable->checkEquals($user->login, $password);
            // create new Auth record
            $authTable->generateEquals($user, $new_password);

            $this->getMessages()->addSuccess("The password was updated successfully");

            // try back to index
            $this->redirectTo('users', 'profile');
        } catch (Exception $e) {
            $this->getMessages()->addError($e->getMessage());
        } catch (AuthException $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
};
