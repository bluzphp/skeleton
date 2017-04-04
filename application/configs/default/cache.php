<?php
/**
 * Cache configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Cache
 * @return array
 */
return [
    "enabled" => true,
    "adapter" => "filesystem",
    "pools" => [
        /**
         * @link https://github.com/php-cache/apc-adapter
         */
        "apc" => function () {
            return new \Cache\Adapter\Apc\ApcCachePool();
        },
        /**
         * @link https://github.com/php-cache/filesystem-adapter
         */
        "filesystem" => function () {
            $filesystemAdapter = new \League\Flysystem\Adapter\Local(PATH_DATA .'/cache');
            $filesystem        = new \League\Flysystem\Filesystem($filesystemAdapter);

            return new \Cache\Adapter\Filesystem\FilesystemCachePool($filesystem);
        },
        /**
         * https://github.com/php-cache/memcached-adapter
         */
        "memcached" => function () {
            $client = new \Memcached();
            $client->addServer('127.0.0.1', 11211);
            return new \Cache\Adapter\Memcached\MemcachedCachePool($client);
        },
        /**
         * @link https://github.com/php-cache/redis-adapter
         */
        "redis" => function () {
            $client = new \Redis();
            $client->connect('127.0.0.1', 6379);
            return new \Cache\Adapter\Redis\RedisCachePool($client);
        },
        /**
         * @link https://github.com/php-cache/predis-adapter
         * @link https://github.com/nrk/predis/wiki/Connection-Parameters
         * @link https://github.com/nrk/predis/wiki/Client-Options
         */
        "predis" => function () {
            $client = new \Predis\Client('tcp:/127.0.0.1:6379');
            return new \Cache\Adapter\Predis\PredisCachePool($client);
        }
    ]
];
