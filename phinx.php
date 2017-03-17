<?php
/**
 * @namespace
 */
namespace Application;

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/data/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => call_user_func(function () {
            $config = new \Bluz\Config\Config();

            $config ->setPath(PATH_APPLICATION);
            $config ->setEnvironment(getenv('BLUZ_ENV') ?: 'production');
            $config ->init();

            $data = $config->getData('db', 'connect');
            $data['adapter'] = $data['type'];

            return $data;
        })
    ]
];
