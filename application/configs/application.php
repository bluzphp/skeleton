<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 * @return   array
 */
return array(
    "profiler" => false,
    "loader" => array(
        "namespaces" => array(
            'Bluz'        => PATH_LIBRARY,
            'Application' => PATH_APPLICATION .'/models'
        ),
        "prefixes" => array(

        ),
    ),
    "cache" => array(
        "cache" => true,
        "servers" => array(
            ["memcached", 11211, 1],
        ),
        "path"  => PATH_DATA .'/cache'
    ),
    "acl" => array(

    ),
    "auth" => array(
        "adapter" => array(
            "name" => "equals",
            "options" => array(
                "encryptFunction" => function($password) {
                    return md5(md5($password) . 'salt');
                }
            )
        )
    ),
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "hippo",
            "name" => "p_bluz",
            "user" => "p_bluz",
            "pass" => "bluz_pass",
        ),
        'defaultAdapter' => true
    ),
    "layout" => array(
        "path" => PATH_APPLICATION .'/layouts',
        "template" => 'index.phtml',
        "helpersPath" => PATH_APPLICATION .'/layouts/helpers',
        "helpers" => array(

        ),
    ),
    "view" => array(

    ),
    "session" => array(
        "store" => "session",
        "options" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    ),
    "request" => array(
        "baseUrl" => '/',
    ),
);
