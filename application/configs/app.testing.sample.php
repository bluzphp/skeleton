<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 */
return array(
    "auth" => array(
        "facebook" => array(
            "appId" => "%%appId%%",
            "secret" => "%%secret%%",
        ),
        "twitter" => array(
            "consumerKey" => "%%consumerKey%%",
            "consumerSecret" => "%%consumerSecret%%"
        )
    ),
    "cache" => array(
        "enabled" => false
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
        "store" => "array"
    )
);
