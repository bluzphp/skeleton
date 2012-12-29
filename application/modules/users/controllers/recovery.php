<?php
/**
 * Initialization password recovery
 *
 * @category Application
 *
 * @author   dark
 * @created  11.12.12 13:03
 */
namespace Application;

use Application\Users;

return
    /**
     * @return \closure
     */
    function ($email = null) use ($view) {
        /**
         * @var \Bluz\Application $this
         */
        // change layout
        $this->useLayout('small.phtml');

        if ($this->getRequest()->isPost()) {
            try {
                // check email
                if (empty($email)) {
                    throw new Exception('Email can\'t be empty');
                }
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    list($user, $domain) = explode("@", $email, 2);
                    if (!checkdnsrr($domain,"MX") && !checkdnsrr($domain,"A")) {
                        throw new Exception('Email has invalid domain name');
                    }
                } else {
                    throw new Exception('Email is invalid');
                }
                // check exists
                $user = Users\Table::getInstance()->findRowWhere(['email' => $email]);
                if (!$user) {
                    throw new Exception('Email not found');
                }
                // check status, only for active users
                if ($user->status != Users\Row::STATUS_ACTIVE) {
                    throw new Exception('User is inactive');
                }

                // create activation token
                // valid for 5 days
                $actionRow = UsersActions\Table::getInstance()->generate($user->id, UsersActions\Row::ACTION_RECOVERY, 5);

                // send activation email
                // generate restore URL
                $resetUrl = $this->getRouter()->getFullUrl(
                    'users',
                    'recovery-reset',
                    ['code' => $actionRow->code, 'id' => $user->id]
                );

                // FIXME: HARDCODED EMAIL TEMPLATE!!!
                $subject =  "Bluz Password Recovery";

                $body = $this->dispatch(
                    'users',
                    'mail-template',
                    [
                        'template' => 'recovery',
                        'vars' => ['user' => $user, 'resetUrl' => $resetUrl]
                    ]
                )->render();

                try {
                    $mail = $this->getMailer()->create();

                    // subject
                    $mail->Subject = $subject;
                    $mail->MsgHTML(nl2br($body));

                    $mail->AddAddress($user->email);

                    $this->getMailer()->send();

                } catch (\Exception $e) {
                    // TODO: log me

                    throw new Exception('Unable to send email. Please contact administrator.');
                }

                // show notification and redirect
                $this->getMessages()->addSuccess(
                    "Reset password instructions has been sent to your email address"
                );
                $this->redirectTo('index', 'index');

            } catch (Exception $e) {
                $this->getMessages()->addError($e->getMessage());
            }
            $view->email = $email;
        }
    };