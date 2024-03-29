<?php

/**
 * Database configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Db
 * @return array
 */

return [
    'connect' => [
        'type' => 'mysql',
        'host' => getenv('MYSQL_HOST') ?: 'localhost',
        'name' => getenv('MYSQL_DATABASE') ?: 'bluz',
        'user' => getenv('MYSQL_USER') ?: 'root',
        'pass' => getenv('MYSQL_PASSWORD') ?: '',
        'options' => [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8'
        ]
    ]
];
