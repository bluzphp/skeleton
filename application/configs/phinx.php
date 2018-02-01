<?php
/**
 * Phinx configuration
 *
 *   Please, don't move this file inside `default` folder
 *   It should be placed here for avoid `Config` call in recursion
 *
 * @link https://github.com/robmorgan/phinx/blob/master/docs/configuration.rst
 * @return array
 */
return [
    'paths' => [
        'migrations' => PATH_DATA . '/migrations',
        'seeds' => PATH_DATA . '/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => (function () {
            $data = \Bluz\Proxy\Config::getData('db', 'connect');
            $data['adapter'] = $data['type'];
            $data['charset'] = 'utf8';
            return $data;
        })()
    ]
];
