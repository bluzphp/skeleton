<?php
/**
 * @namespace
 */
namespace Application;

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/migrations'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',

        'default' => getConfigByEnvironment('default'),
        'travis' =>  getConfigByEnvironment('default'),
    ]
];
