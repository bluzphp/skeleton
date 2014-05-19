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

return
/**
 * @var Bootstrap $this
 * @privilege EditEmail
 * @return void
 */
function ($email = null, $password = null, $token = null) use ($view) {
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

    $view->email = $user->email;

    if ($this->getRequest()->isPost()) {
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

            $changeUrl = $this->getRouter()->getFullUrl(
                'users',
                'change-email',
                ['token' => $actionRow->code]
            );

            $subject = __("Change email");

            $body = $this->dispatch(
                'users',
                'mail-template',
                [
                    'template' => 'change-email',
                    'vars' => [
                        'user' => $user,
                        'email' => $email,
                        'changeUrl' => $changeUrl,
                        'profileUrl' => $this->getRouter()->getFullUrl('users', 'profile')
                    ]
                ]
            )->render();

            try {
                $mail = $this->getMailer()->create();

                // subject
                $mail->Subject = $subject;
                $mail->MsgHTML(nl2br($body));

                $mail->AddAddress($email);

                $this->getMailer()->send($mail);

                $this->getMessages()->addNotice('Check your email and follow instructions in letter.');

            } catch (\Exception $e) {
                $this->getLogger()->log(
                    'error',
                    $e->getMessage(),
                    ['module' => 'users', 'controller' => 'change-email', 'userId' => $userId]
                );
                throw new Exception('Unable to send email. Please contact administrator.');
            }

            // try back to index
            $this->redirectTo('users', 'profile');
        } catch (Exception $e) {
            $this->getMessages()->addError($e->getMessage());
            $view->email = $email;
        } catch (AuthException $e) {
            $this->getMessages()->addError($e->getMessage());
            $view->email = $email;
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

        $this->getMessages()->addSuccess('Email was updated');
        $this->redirectTo('users', 'profile');
    }
};
