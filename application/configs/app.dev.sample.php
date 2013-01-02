<?php
/**
 * Application config for developer
 *
 * @author   Anton Shevchuk
 * @created  18.09.12 11:14
 * @return   array
 */
return array(
    "auth" => array(
        "equals" => array(
            "encryptFunction" => function($password, $salt) {
                return md5(md5($password) . $salt);
            }
        )
    ),
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
    "mail" => array(
        "subjectPrefix" => "Bluz - ",
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
    ),
    "session" => array(
        "options" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    )
);
