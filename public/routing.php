<?php
/**
 * Routing file
 *
 * Need for correct start build-in web-server
 *
 * @link http://php.net/manual/en/features.commandline.webserver.php
 */
if (file_exists(__DIR__ . $_SERVER['REQUEST_URI'])
    || preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER['REQUEST_URI'])
    || preg_match('/\.(?:js|css)$/', $_SERVER['SCRIPT_NAME'])
) {
    return false;    // serve the requested resource as-is.
}

include_once __DIR__.'/index.php';
