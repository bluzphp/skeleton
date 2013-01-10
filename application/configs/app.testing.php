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
            "encryptFunction" => function($password, $salt) {
                return md5(md5($password) . $salt);
            }
        )
    ),
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "",
            "name" => "",
            "user" => "",
            "pass" => "",
        ),
    ),
    "profiler" => false,
    "cache" => array(
        "enabled" => false
    ),
    "session" => array(
        "store" => "array"
    )
);
