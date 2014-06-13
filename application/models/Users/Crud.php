<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Users;

use Application\Auth;
use Application\Exception;
use Application\UsersActions;
use Bluz\Crud\ValidationException;
use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

/**
 * Crud
 *
 * @package  Application\Users
 *
 * @method   Table getTable()
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 16:11
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * @param $data
     * @throws \Application\Exception
     * @return integer
     */
    public function createOne($data)
    {
        $this->validate(null, $data);
        $this->validateCreate($data);
        $this->checkErrors();

        /** @var $row Row */
        $row = $this->getTable()->create();
        $row->setFromArray($data);
        $row->status = Table::STATUS_PENDING;
        $row->save();

        $userId = $row->id;

        // create auth
        $password = isset($data['password'])?$data['password']:null;
        Auth\Table::getInstance()->generateEquals($row, $password);

        // create activation token
        // valid for 5 days
        $actionRow = UsersActions\Table::getInstance()->generate($userId, UsersActions\Table::ACTION_ACTIVATION, 5);

        // send activation email
        // generate activation URL
        $activationUrl = app()->getRouter()->getFullUrl(
            'users',
            'activation',
            ['code' => $actionRow->code, 'id' => $userId]
        );

        $subject = "Activation";

        $body = app()->dispatch(
            'users',
            'mail-template',
            [
                'template' => 'registration',
                'vars' => ['user' => $row, 'activationUrl' => $activationUrl, 'password' => $password]
            ]
        )->render();

        try {
            $mail = app()->getMailer()->create();

            // subject
            $mail->Subject = $subject;
            $mail->MsgHTML(nl2br($body));

            $mail->AddAddress($data['email']);

            app()->getMailer()->send($mail);

        } catch (\Exception $e) {
            app()->getLogger()->log(
                'error',
                $e->getMessage(),
                ['module' => 'users', 'controller' => 'change-email', 'userId' => $userId]
            );

            throw new Exception('Unable to send email. Please contact administrator.');
        }

        // show notification and redirect
        app()->getMessages()->addSuccess(
            "Your account has been created and an activation link has".
            "been sent to the e-mail address you entered.<br/>".
            "Note that you must activate the account by clicking on the activation link".
            "when you get the e-mail before you can login."
        );
        app()->redirectTo('index', 'index');

        return $userId;
    }

    /**
     * @throws ValidationException
     */
    public function validate($id, $data)
    {
        $validator = new ValidatorBuilder();
        $validator->add(
            'login',
            v::required()->latin()->length(3, 255),
            v::callback(function ($login) use ($id) {
                $user = $this->getTable()
                    ->select()
                    ->where('login = ?', $login)
                    ->andWhere('id != ?', $id)
                    ->execute();
                return !$user;
            })->setError('User with login "{{input}}" already exists')
        );
        $validator->add(
            'email',
            v::required()->email(true),
            v::callback(function ($email) use ($id) {
                $user = $this->getTable()
                    ->select()
                    ->where('email = ?', $email)
                    ->andWhere('id != ?', $id)
                    ->execute();
                return !$user;
            })->setError('User with email "{{input}}" already exists')
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateCreate($data)
    {
        // password
        $password = isset($data['password'])?$data['password']:null;
        $password2 = isset($data['password2'])?$data['password2']:null;
        if (empty($password)) {
            $this->addError('Password can\'t be empty', 'password');
        }

        if ($password !== $password2) {
            $this->addError('Password is not equal', 'password2');
        }
    }
}
