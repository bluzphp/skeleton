<?php
/**
 * Cache configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Cache
 * @return array
 */
return array(
    "enabled" => false,
    "adapter" => "memcached",
    "settings" => array(
        "apc" => array(),
        "memcached" => array(
            "persistent" => "uid",
            "servers" => [
                ["localhost", 11211, 1],
            ]
        ),
        "phpFile" => array(
            "cacheDir" => PATH_DATA . '/cache'
        ),
        "redis" => array(
            "host" => 'localhost',
            "options" => array(
                \Redis::OPT_SERIALIZER => \Redis::SERIALIZER_PHP
            )
        ),
    )
);
