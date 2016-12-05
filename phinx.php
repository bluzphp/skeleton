<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/migrations'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => 'bluz',

        'default' => getConfigByEnvironment('default'),
        'travis' =>  getConfigByEnvironment('default'),
    ]
];


function getConfigByEnvironment($env)
{
    $config = new Bluz\Config\Config();

    $config ->setPath(PATH_APPLICATION);
    $config ->setEnvironment($env);
    $config ->init();

    $data = $config->getData('db', 'connect');
    $data['adapter'] = $data['type'];

    return $data;
}
