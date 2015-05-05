<?php
/**
 * Auth configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Auth
 * @return array
 */
return array(
    "equals" => array(
        "encryptFunction" => function ($password, $salt) {
            return md5(md5($password) . $salt);
        }
    ),
    "facebook" => array(
        "appId" => "1413483462306154",
        "secret" => "0911925061bdb04a3d8c41129f672065",
    ),
    "twitter" => array(
        "consumer_key" => "%%consumerKey%%",
        "consumer_secret" => "%%consumerSecret%%"
    ),
    "google" => array(
        'client_id' => "%%client_id%%",
        'client_secret' => '%%client_secret%%'
    )
);
