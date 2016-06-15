<?php
/**
 * Example of Mailer usage
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Mailer;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

/**
 * @param string $email
 * @return void
 */
return function ($email = "no-reply@nixsolutions.com") {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Mailer Example',
        ]
    );
    if (Request::isPost()) {
        try {
            $mail = Mailer::create();
            // subject
            $mail->Subject = "Example of Bluz Mailer";
            $mail->msgHTML("Hello!<br/>How are you?");
            $mail->addAddress($email);
            Mailer::send($mail);
            Messages::addSuccess("Email was send");
        } catch (\Exception $e) {
            Messages::addError($e->getMessage());
        }
    }
    $this->assign('email', $email);
};
