<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 * @return   array
 */
return array(
    "cache" => array(
        "cache" => true,
        "servers" => array(
            ["memcached", 11211, 1],
        )
    ),
    "auth" => array(
        "equals" => array(
            "encryptFunction" => function($password, $salt) {
                return md5(md5($password) . $salt);
            }
        ),
    ),
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "hippo",
            "name" => "p_bluz",
            "user" => "p_bluz",
            "pass" => "bluz_pass",
        )
    ),
    "session" => array(
        "options" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    ),
);
