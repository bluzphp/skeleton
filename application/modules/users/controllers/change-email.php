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
use Application\UsersActions\Table;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Mailer;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;

/**
 * @privilege EditEmail
 *
 * @param $email
 * @param $password
 * @param $token
 * @return void
 * @throws Exception
 * @throws NotFoundException
 */
return function ($email = null, $password = null, $token = null) {
    /**
     * @var Controller $this
     */
    // change layout
    $this->useLayout('small.phtml');

    $userId = $this->user() ? $this->user()->id : null;

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($userId);

    if (!$user) {
        throw new NotFoundException('User not found');
    }

    $this->assign('email', $user->email);

    if (Request::isPost()) {
        // process form
        try {
            if (empty($password)) {
                throw new Exception('Password is empty');
            }

            // login/password
            Auth\Table::getInstance()->checkEquals($user->login, $password);

            // check email for unique
            $emailUnique = Users\Table::findRowWhere(['email' => $email]);
            if ($emailUnique && $emailUnique->id != $userId) {
                throw new Exception('User with email "'.htmlentities($email).'" already exists');
            }

            // generate change mail token and get full url
            $actionRow = UsersActions\Table::getInstance()->generate(
                $userId,
                Table::ACTION_CHANGE_EMAIL,
                5,
                ['email' => $email]
            );

            $changeUrl = Router::getFullUrl(
                'users',
                'change-email',
                ['token' => $actionRow->code]
            );

            $subject = __("Change email");

            $body = $this->dispatch(
                'users',
                'mail/template',
                [
                    'template' => 'change-email',
                    'vars' => [
                        'user' => $user,
                        'email' => $email,
                        'changeUrl' => $changeUrl,
                        'profileUrl' => Router::getFullUrl('users', 'profile')
                    ]
                ]
            )->render();

            try {
                $mail = Mailer::create();
                $mail->Subject = $subject;
                $mail->msgHTML(nl2br($body));
                $mail->addAddress($email);
                Mailer::send($mail);

                Messages::addNotice('Check your email and follow instructions in letter.');
            } catch (\Exception $e) {
                Logger::log(
                    'error',
                    $e->getMessage(),
                    ['module' => 'users', 'controller' => 'change-email', 'userId' => $userId]
                );
                throw new Exception('Unable to send email. Please contact administrator.');
            }

            // try back to index
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
        $actionRow = UsersActions\Table::findRowWhere(['code' => $token, 'userId' => $userId]);

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
