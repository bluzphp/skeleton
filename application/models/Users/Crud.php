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
use Bluz\Application\Application;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Mailer;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Router;
use Bluz\Validator\Exception\ValidatorException;

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
     * @param array $data
     * @throws Exception
     * @throws ValidatorException
     * @return integer
     */
    public function createOne($data)
    {
        // password
        $password = isset($data['password']) ? $data['password'] : null;
        $password2 = isset($data['password2']) ? $data['password2'] : null;

        if (empty($password)) {
            throw ValidatorException::exception('password', __('Password can\'t be empty'));
        }

        if ($password !== $password2) {
            throw ValidatorException::exception('password2', __('Password is not equal'));
        }

        if ($data['id'] == '') {
            unset($data['id']);
        }

        /** @var $row Row */
        $row = $this->getTable()->create();
        $row->setFromArray($data);
        $row->status = Table::STATUS_PENDING;
        $row->save();

        $userId = $row->id;

        // create auth
        Auth\Table::getInstance()->generateEquals($row, $password);

        // create activation token
        // valid for 5 days
        $actionRow = UsersActions\Table::getInstance()->generate($userId, UsersActions\Table::ACTION_ACTIVATION, 5);

        // send activation email
        // generate activation URL
        $activationUrl = Router::getFullUrl(
            'users',
            'activation',
            ['code' => $actionRow->code, 'id' => $userId]
        );

        $subject = "Activation";

        $body = Application::getInstance()->dispatch(
            'users',
            'mail/template',
            [
                'template' => 'registration',
                'vars' => ['user' => $row, 'activationUrl' => $activationUrl, 'password' => $password]
            ]
        )->render();

        try {
            $mail = Mailer::create();
            $mail->Subject = $subject;
            $mail->msgHTML(nl2br($body));
            $mail->addAddress($data['email']);
            Mailer::send($mail);
        } catch (\Exception $e) {
            Logger::log(
                'error',
                $e->getMessage(),
                ['module' => 'users', 'controller' => 'change-email', 'userId' => $userId]
            );

            throw new Exception('Unable to send email. Please contact administrator.');
        }

        // show notification and redirect
        Messages::addSuccess(
            "Your account has been created and an activation link has" .
            "been sent to the e-mail address you entered.<br/>" .
            "Note that you must activate the account by clicking on the activation link" .
            "when you get the e-mail before you can login."
        );
        // wtf?
        // redirectTo('index', 'index');

        return $userId;
    }
}
