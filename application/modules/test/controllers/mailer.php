<?php
/**
 * Example of Mailer usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

return
/**
 * @param string $email
 * @return \closure
 */
function ($email = "no-reply@nixsolutions.com") use ($view) {
    /**
     * @var Bootstrap $this
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Mailer Example',
        ]
    );
    if ($this->getRequest()->isPost()) {
        try {
            $mail = $this->getMailer()->create();
            // subject
            $mail->Subject = "Example of Bluz Mailer";
            $mail->MsgHTML("Hello!<br/>How are you?");
            $mail->AddAddress($email);
            $this->getMailer()->send($mail);
            $this->getMessages()->addSuccess("Email was send");
        } catch (\Exception $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
    $view->email = $email;
};
