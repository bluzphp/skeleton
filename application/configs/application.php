<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 * @return   array
 */
return array(
    "cache" => array(
        "cache" => true,
        "path"  => PATH_DATA .'/cache'
    ),
    "db" => array(
        "defaultAdapter" => true
    ),
    "layout" => array(
        "path" => PATH_APPLICATION .'/layouts',
        "template" => 'index.phtml',
        "helpersPath" => PATH_APPLICATION .'/layouts/helpers'
    ),
    "profiler" => DEBUG,
    "request" => array(
        "baseUrl" => '/',
    ),
    "session" => array(
        "store" => "session"
    ),
);
