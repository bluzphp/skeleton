<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Users;

use Application\Exception;
use Application\UsersActions;
use Bluz\Application\Application;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Mailer;
use Bluz\Proxy\Router;

/**
 * Mail for Users
 *
 * @package  Application\Users
 */
class Mail
{
    /**
     * Activation
     *
     * @param Row $user
     * @param string $password
     *
     * @return bool
     * @throws \Bluz\Common\Exception\ComponentException
     * @throws \Bluz\Common\Exception\CommonException
     * @throws \Bluz\Controller\ControllerException
     * @throws \Bluz\Http\Exception\NotAllowedException
     * @throws \Bluz\Http\Exception\NotAcceptableException
     * @throws \Bluz\Http\Exception\ForbiddenException
     */
    public static function activation($user, $password): ?bool
    {
        // email subject
        $subject = __('Activation');

        // create activation token
        // valid for 5 days
        $actionRow = UsersActions\Table::getInstance()->generate(
            $user->id,
            UsersActions\Table::ACTION_ACTIVATION
        );

        // send activation email
        // generate activation URL
        $activationUrl = Router::getFullUrl(
            'users',
            'activation',
            ['code' => $actionRow->code, 'id' => $user->id]
        );

        // generate mail template by controller
        $body = Application::getInstance()->dispatch(
            'users',
            'mail/template',
            [
                'template' => 'registration',
                'vars' => ['user' => $user, 'activationUrl' => $activationUrl, 'password' => $password]
            ]
        )->render();

        // try to send email
        try {
            $mail = Mailer::create();
            $mail->Subject = $subject;
            $mail->msgHTML(nl2br($body));
            $mail->addAddress($user->email);

            return Mailer::send($mail);
        } catch (\Exception $e) {
            Logger::log(
                'error',
                $e->getMessage(),
                ['module' => 'users', 'controller' => 'change-email', 'userId' => $user->id]
            );

            return false;
        }
    }

    /**
     * Password Recovery
     *
     * @param Row $user
     *
     * @return bool
     * @throws \Bluz\Common\Exception\ComponentException
     * @throws \Bluz\Common\Exception\CommonException
     * @throws \Bluz\Controller\ControllerException
     * @throws \Bluz\Http\Exception\NotAllowedException
     * @throws \Bluz\Http\Exception\NotAcceptableException
     * @throws \Bluz\Http\Exception\ForbiddenException
     */
    public static function recovery($user): ?bool
    {
        // email subject
        $subject = __('Password Recovery');

        // create activation token
        // valid for 5 days
        $actionRow = UsersActions\Table::getInstance()->generate(
            $user->id,
            UsersActions\Table::ACTION_RECOVERY
        );

        // send activation email
        // generate restore URL
        $resetUrl = Router::getFullUrl(
            'users',
            'recovery-reset',
            ['code' => $actionRow->code, 'id' => $user->id]
        );


        $body = Application::getInstance()->dispatch(
            'users',
            'mail/template',
            [
                'template' => 'recovery',
                'vars' => ['user' => $user, 'resetUrl' => $resetUrl]
            ]
        )->render();

        try {
            $mail = Mailer::create();
            $mail->Subject = $subject;
            $mail->msgHTML(nl2br($body));
            $mail->addAddress($user->email);

            return Mailer::send($mail);
        } catch (\Exception $e) {
            // log it
            Logger::log(
                'error',
                $e->getMessage(),
                ['module' => 'users', 'controller' => 'recovery', 'email' => $user->email]
            );
            return false;
        }
    }

    /**
     * Change email
     *
     * @param Row $user
     * @param string $email
     *
     * @return bool
     * @throws \Bluz\Common\Exception\ComponentException
     * @throws \Bluz\Common\Exception\CommonException
     * @throws \Bluz\Controller\ControllerException
     * @throws \Bluz\Http\Exception\NotAllowedException
     * @throws \Bluz\Http\Exception\NotAcceptableException
     * @throws \Bluz\Http\Exception\ForbiddenException
     */
    public static function changeEmail($user, $email): ?bool
    {
        // email subject
        $subject = __('Change email');

        // generate change mail token and get full url
        $actionRow = UsersActions\Table::getInstance()->generate(
            $user->id,
            UsersActions\Table::ACTION_CHANGE_EMAIL,
            5, // ttl in days
            ['email' => $email]
        );

        $changeUrl = Router::getFullUrl(
            'users',
            'change-email',
            ['token' => $actionRow->code]
        );

        $body = Application::getInstance()->dispatch(
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

            return Mailer::send($mail);
        } catch (\Exception $e) {
            Logger::log(
                'error',
                $e->getMessage(),
                ['module' => 'users', 'controller' => 'change-email', 'userId' => $user->id]
            );
            return false;
        }
    }
}
