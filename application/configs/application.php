<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 * @return   array
 */
return array(
    "auth" => array(
        "equals" => array(
            "encryptFunction" => function ($password, $salt) {
                return md5(md5($password) . $salt);
            }
        )
    ),
    "cache" => array(
        "enabled" => true,
        "settings" => array(
            "cacheAdapter" => array(
                "name" => "memcached",
                "settings" => array(
                    "servers" => [
                        ["memcached", 11211, 1],
                    ]
                )
            )
        )
    ),
    "db" => array(
        "defaultAdapter" => true
    ),
    "layout" => array(
        "path" => PATH_APPLICATION .'/layouts',
        "template" => 'index.phtml',
        "helpersPath" => PATH_APPLICATION .'/layouts/helpers'
    ),
    "logger" => array(
        "enabled" => false,
    ),
    "mailer" => array(
        "subjectTemplate" => "Bluz - %s",
        "from" => [
            "email" => "no-reply@example.com",
            "name" => "Bluz"
        ],
        // PHPMailer settings
        // read more at https://github.com/Synchro/PHPMailer
        "settings" => [
            "CharSet" => "UTF-8"
        ],
    ),
    "profiler" => DEBUG,
    "request" => array(
        "baseUrl" => '/',
    ),
    "session" => array(
        "store" => "session",
        "settings" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    ),
    "translator" => array(
        "domain" => "messages",
        "locale" => "en_US",
        "path" => PATH_DATA .'/locale'
    )
);
