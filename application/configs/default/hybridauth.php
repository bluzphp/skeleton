<?php
/**
 * @author yuklia
 * @link   http://hybridauth.sourceforge.net/userguide/Configuration.html
 * @return array
 */
return [
    // "base_url" the url that point to HybridAuth Endpoint (where index.php and config.php are found)
    "base_url" => "%%domain%%/auth/endpoint",

    "providers" => [
        "Google" => [
            "enabled" => true,
            "wrapper" => [
                "path" => "Providers/Google.php",
                "class" => "Hybrid_Providers_Google"
            ],
            "keys" => [ // 'id' is your google client id
                "id" => "%%client_id%%",
                "secret" => "%%client_secret%%"
            ],
            "scope"   => "https://www.googleapis.com/auth/userinfo.profile ". // optional
                "https://www.googleapis.com/auth/userinfo.email"   , // optional
            "access_type"     => "offline",   // optional
            "approval_prompt" => "force",     // optional
        ],
        "Facebook" => [
            "enabled" => true,
            "wrapper" => [
                "path" => "Providers/Facebook.php",
                "class" => "Hybrid_Providers_Facebook"
            ],
            "keys"  => [ // 'id' is your facebook application id
                "id" => "%%appId%%",
                "secret" => "%%secret%%"
            ],
            "scope" => "email, user_about_me, user_birthday, user_hometown, publish_actions", // optional
        ],
        "Twitter" => [
            "enabled" => true,
            "wrapper" => [
                "path" => "Providers/Twitter.php",
                "class" => "Hybrid_Providers_Twitter"
            ],
            "keys" => [ // 'key' is your twitter application consumer key
                "key" => "%%consumerKey%%",
                "secret" => "%%consumerSecret%%"
            ]
        ]
    ],
    // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
    "debug_mode" => false,
    "debug_file" =>''
];
