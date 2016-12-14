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
    "pools" => array(
        /**
         * @link https://github.com/php-cache/apc-adapter
         */
        "apc" => function() {
            return new \Cache\Adapter\Apc\ApcCachePool();
        },
        /**
         * @link https://github.com/php-cache/filesystem-adapter
         */
        "filesystem" => function() {
            $filesystemAdapter = new \League\Flysystem\Adapter\Local(PATH_DATA . '/cache');
            $filesystem        = new \League\Flysystem\Filesystem($filesystemAdapter);

            return new \Cache\Adapter\Filesystem\FilesystemCachePool($filesystem);
        },
        /**
         * @link https://github.com/php-cache/predis-adapter
         * @link https://github.com/nrk/predis/wiki/Connection-Parameters
         * @link https://github.com/nrk/predis/wiki/Client-Options
         */
        "predis" => function() {
            $client = new \Predis\Client('tcp:/127.0.0.1:6379');
            return new \Cache\Adapter\Predis\PredisCachePool($client);
        }
    ),


    "prefix" => "bluz:",
    "tagPrefix" => "bluz:@:",
    "settings" => array(
        "apc" => array(),
        /**
         * @link http://php.net/manual/en/memcached.addservers.php
         * @link http://php.net/manual/en/memcached.setoptions.php
         */
        "memcached" => array(
            "persistent" => "uid",
            "servers" => [
                ["localhost", 11211, 1],
            ],
            "options" => array()
        ),
        "phpFile" => array(
            "cacheDir" => PATH_DATA . '/cache'
        ),
        /**
         * @link https://github.com/nicolasff/phpredis#connection
         * @link https://github.com/nicolasff/phpredis#setoption
         */
        "redis" => array(
            "host" => 'localhost',
            "options" => array()
        ),
        /**
         * @link https://github.com/nrk/predis/wiki/Connection-Parameters
         * @link https://github.com/nrk/predis/wiki/Client-Options
         */
        "predis" => array(
            "host" => 'localhost',
            "options" => array()
        )
    )
);
