<?php
/**
 * Initialization password recovery
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  11.12.12 13:03
 */

namespace Application;

use Application\Users;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Validator\Validator;

/**
 * @param string $email
 *
 * @return void
 */
return function ($email = null) {
    /**
     * @var Controller $this
     */
    // change layout
    $this->useLayout('small.phtml');

    if (Request::isPost()) {
        try {
            // check email
            Validator::create()
                ->notEmpty()
                ->email(true)
                ->assert($email);

            // check exists
            $user = Users\Table::findRowWhere(['email' => $email]);
            if (!$user) {
                throw new Exception('Email not found');
            }

            // check status, only for active users
            if ($user->status !== Users\Table::STATUS_ACTIVE) {
                throw new Exception('User is inactive');
            }

            // generate change mail token and get full url
            if (Users\Mail::recovery($user)) {
                Messages::addNotice('Reset password instructions has been sent to your email address');
            } else {
                Messages::addError('Unable to send email. Please contact administrator');
            }

            // show notification and redirect
            Response::redirectTo('index', 'index');
        } catch (Exception $e) {
            Messages::addError($e->getMessage());
        }
        $this->assign('email', $email);
    }
};
