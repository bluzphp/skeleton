<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application\Users;

use \Bluz\Crud\ValidationException;
use \Bluz\Crud\CrudException;

use Application\Auth;
use Application\Exception;
use Application\UsersActions;

/**
 * Crud
 *
 * @category Application
 * @package  Users
 *
 * @author   Sergey Volkov
 * @created  29.05.2013 12:24
 */
class CrudProfile extends \Bluz\Crud\Crud
{
    /**
     * @param array $formData           Data from form
     * @param string $inputPassword     Encrypted password, which user enter
     * @param string $userPassword      Correct password from DB
     * @param string $userEmail         User email from DB
     * @return bool                     Return true, if profile doesn't changed, false - if changed
     */
    protected function isProfileNotChanged($formData, $inputPassword, $userPassword, $userEmail)
    {
        return ((empty($formData['old_password']) || $inputPassword == $userPassword)
            && empty($formData['new_password'])
            && empty($formData['new_password2'])
            && $formData['email'] === $userEmail);
    }

    /**
     * @throws ValidationException
     */
    public function validateUpdate($originalRow)
    {
        $application = $this->getApplication();

        $options = $application->getConfigData('auth', 'equals');

        $userId = $application->getAuth()->getIdentity()->id;
        $userRow = $this->getTable()->findRow($userId);
        $authRow = Auth\Table::findRowWhere(['userId' => $userId]);

        // init form data
        $email = $this->getData('email');
        $oldPassword = $this->getData('old_password');
        $newPassword = $this->getData('new_password');
        $newPassword2 = $this->getData('new_password2');

        // encrypt input password
        $inputPassword = call_user_func($options['encryptFunction'], $oldPassword, $authRow->tokenSecret);

        // if user make changes
        if (!$this->isProfileNotChanged($this->data, $inputPassword, $authRow->token, $userRow->email)) {
            // email validator
            if (empty($email)) {
                $this->addError('email', 'Email can\'t be empty');
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                list($user, $domain) = explode("@", $email, 2);
                if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
                    $this->addError('email', 'Email has invalid domain name');
                }
            } else {
                $this->addError('email', 'Email is invalid');
            }

            // check email for unique
            $emailUnique = $this->getTable()->findRowWhere(['email' => $email]);
            if ($emailUnique && $emailUnique->id != $userId) {
                $this->addError('email', 'User with email "'.htmlentities($email).'" already exists');
            }

            // passwords validator
            if (empty($oldPassword)) {
                $this->addError('old_password', 'Password can\'t be empty');
            }
            if ($authRow->token !== $inputPassword) {
                $this->addError('old_password', 'Wrong password');
            }
            if ($newPassword2 !== $newPassword) {
                $this->addError('new_password2', 'Password is not equal');
            }
        }

        // validate entity
        if (sizeof($this->errors)) {
            throw new ValidationException('Validation error, please check errors stack');
        }
    }

    public function read()
    {
        $primary = $this->getPrimaryKey();

        // nothing? ok, new row please
        if (!sizeof($primary)) {
            return null;
        }

        $row = $this->getTable()->findRow($primary);

        if (!$row) {
            throw new CrudException("Record not found");
        }

        // if user changing email now, show him a hint
        $changeEmail = UsersActions\Table::findRowWhere(
            [
                'userId' => $row->id,
                'action' => UsersActions\Row::ACTION_CHANGE_EMAIL
            ]
        );
        if ($changeEmail) {
            $row->changeEmail = true;
        }

        return $row;
    }

    /**
     * @throws Exception
     * @return boolean
     */
    public function update()
    {
        $this->validateUpdate($this->data);

        $application = $this->getApplication();
        $router = $application->getRouter();
        $mailer = $application->getMailer();

        $userId = $this->data['id'];
        $options = $this->getApplication()->getConfigData('auth', 'equals');

        $authRow = Auth\Table::findRowWhere(['userId' => $this->data['id']]);
        $usersRow = $this->getTable()->findRowWhere(['id' => $userId]);

        // change password
        if (!empty($this->data['new_password'])) {
            $newPassword = call_user_func(
                $options['encryptFunction'],
                $this->data['new_password'],
                $authRow->tokenSecret
            );
            $authRow->token = $newPassword;
            $authRow->save();
            $application->getMessages()->addSuccess('Password has been changed');
        }

        /**
         * If user changed email, save new in cookie (5 days expired).
         * Then generate mail token and send email.
         * When user go to link, in users/edit check this and replace email.
         */
        if ($usersRow->email !== $this->data['email']) {
            // cookie expire time - 5 days
            $cookieExpireTime = time() + 86400*5;
            setcookie('new-email', $this->data['email'], $cookieExpireTime, '/');

            // generate change mail token and get full url
            $actionRow = UsersActions\Table::getInstance()->generate(
                $userId,
                UsersActions\Row::ACTION_CHANGE_EMAIL,
                5
            );

            $changeUrl = $router->getFullUrl(
                'users',
                'edit',
                ['email' => $actionRow->code, 'id' => $userId]
            );

            $subject = "Change email";

            $body = $application->dispatch(
                'users',
                'mail-template',
                [
                    'template' => 'change-email',
                    'vars' => [
                        'user' => $usersRow,
                        'email' => $this->data['email'],
                        'changeUrl' => $changeUrl,
                        'profileUrl' => $router->getFullUrl('users', 'edit')
                    ]
                ]
            )->render();

            try {
                $mail = $mailer->create();

                // subject
                $mail->Subject = $subject;
                $mail->MsgHTML(nl2br($body));

                $mail->AddAddress($usersRow->email);

                $mailer->send($mail);
                $application->getMessages()->addNotice(__('Check your email and follow instructions in letter.'));

            } catch (\Exception $e) {
                // TODO: log me
                throw new Exception('Unable to send email. Please contact administrator.');
            }
        }


        // disable default crud behaviour
        return false;
    }
}
