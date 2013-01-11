<?php
/**
 * Application config
 * 
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 */
return array(
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "localhost",
            "name" => "bluz",
            "user" => "root",
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
