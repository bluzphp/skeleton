<?php

/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */

namespace Application;

use Application\Users;
use Application\UsersActions;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * @privilege EditEmail
 *
 * @param $email
 * @param $password
 * @param $token
 *
 * @return void
 * @throws Exception
 * @throws NotFoundException
 */
return function ($email = null, $password = null, $token = null) {
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
                throw new Exception('Password is empty');
            }

            // password check
            Auth\Provider\Equals::verify($user->login, $password);

            // is current email?
            if ($user->email === $email) {
                throw new Exception('Email address is equal to current');
            }

            // is unique email?
            if (Users\Table::findRowWhere(['email' => $email])) {
                throw new Exception('User with email "' . htmlentities($email) . '" already exists');
            }

            // generate change mail token and get full url
            if (Users\Mail::changeEmail($user, $email)) {
                Messages::addNotice('Check your email and follow instructions in letter');
            } else {
                Messages::addError('Unable to send email. Please contact administrator');
            }

            // send email
            Response::redirectTo('users', 'profile');
        } catch (Exception $e) {
            Messages::addError($e->getMessage());
            $this->assign('email', $email);
        } catch (AuthException $e) {
            Messages::addError($e->getMessage());
            $this->assign('email', $email);
        }
    } elseif ($token) {
        // process activation
        $actionRow = UsersActions\Table::findRowWhere(['code' => $token, 'userId' => $user->id]);

        if (!$actionRow) {
            throw new Exception('Invalid token');
        }

        $params = $actionRow->getParams();

        $user->email = $params['email'];
        $user->save();

        $actionRow->delete();

        Messages::addSuccess('Email was updated');
        Response::redirectTo('users', 'profile');
    }
};
