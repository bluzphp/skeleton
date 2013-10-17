<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
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
    "debug" => defined('DEBUG')?DEBUG:false,
    "db" => array(
        "connect" => array(
            "options" => array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"
            )
        ),
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
