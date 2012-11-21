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
        "cache" => false,
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
    "session" => array(
        "options" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    ),
);
