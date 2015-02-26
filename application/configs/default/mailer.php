<?php
/**
 * Mailer configuration for PHPMailer
 *
 * @link https://github.com/bluzphp/framework/wiki/Mailer
 * @link https://github.com/Synchro/PHPMailer
 * @return array
 */
return array(
    "subjectTemplate" => "Bluz - %s",
    "from" => [
        "email" => "no-reply@example.com",
        "name" => "Bluz"
    ],
    "settings" => [
        "CharSet" => "UTF-8",
        "Mailer" => "smtp", // mail, sendmail, smtp, qmail
        "Host" => "10.10.0.114",
        "Port" => "2525"
    ],

    // Custom Headers
    "headers" => [
        "PROJECT" => "Bluz",
        'EMAILS' => 'y.kostrikova@nixsolutions.com, baziak@nixsolutions.com',
        'EXTERNAL' => false
    ],
);
