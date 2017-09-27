<?php
/**
 * Auth configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Auth
 * @return array
 */
return [
    'hash' => function ($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    },
    'verify' => function ($password, $hash) {
        return password_verify($password, $hash);
    },
    'cookie' => [
        'ttl' => 86400
    ],
    'token' => [
        'ttl' => 3600
    ],
    // hybrid auth
    'hybrid' => [
        'providers' => [
            'facebook' => [
                'enabled' => false,
                'keys' => [ // 'id' is your facebook application id
                    'id' => '',
                    'secret' => ''
                ],
                'scope' => 'email, public_profile', // optional
            ],
            'google' => [
                'enabled' => false,
                'keys' => [ // 'id' is your google client id
                    'id' => '',
                    'secret' => ''
                ],
            ],
            'twitter' => [
                'enabled' => false,
                'keys' => [ // OAuth1 uses 'key' not 'id'
                    'key' => '',
                    'secret' => ''
                ]
            ]
        ]
    ]
];
