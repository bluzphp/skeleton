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
            return password_hash($password, PASSWORD_DEFAULT, ['salt' => $salt]);
        }
    )
);
