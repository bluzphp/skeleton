<?php
/**
 * Auth configuration
 *
 * @link https://github.com/bluzphp/framework/wiki/Auth
 * @return array
 */
return array(
    "equals" => array(
        "hash" => function ($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        },
        "verify" => function ($password, $hash) {
            return password_verify($password, $hash);
        }
    )
);
