<?php
/**
 * Application config for developer
 *
 * @author   Anton Shevchuk
 * @created  18.09.12 11:14
 * @return   array
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
    "mailer" => array(
        "subjectTemplate" => "Bluz - %s",
        "from" => [
            "email" => "no-reply@nixsolutions.com",
            "name" => "Bluz"
        ],
        "smtp" => [
            "host" => "",
            "port" => "",
            "username" => "",
            "password" => ""
        ]
    )
);
