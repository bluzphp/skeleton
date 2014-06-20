<?php
/**
 * Application config for developer
 *
 * @author   Anton Shevchuk
 * @created  18.09.12 11:14
 */
return array(
    "cache" => array(
        "enabled" => false,
    ),
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "localhost",
            "name" => "bluz",
            "user" => "root",
            "pass" => "",
        ),
    ),
    "logger" => array(
        "enabled" => true,
    ),
    "mailer" => array(
        "subjectTemplate" => "Bluz - %s",
        "from" => [
            "email" => "no-reply@nixsolutions.com",
            "name" => "Bluz"
        ],
        // PHPMailer settings
        // read more at https://github.com/Synchro/PHPMailer
        "settings" => [
            "CharSet" => "UTF-8",
            "Mailer" => "smtp", // mail, sendmail, smtp, qmail
            "SMTPSecure" => "ssl",
            "Host" => "localhost",
            "Port" => "2525",
            "SMTPAuth" => true,
            "Username" => "",
            "Password" => "",
        ],
    ),
    "upload_dir" => array(
        "path" => PATH_PUBLIC."/uploads"
    )
);
