<?php
/**
 * Routing file
 * Need for correct start build-in web-server
 * @link http://php.net/manual/en/features.commandline.webserver.php
 */
if (file_exists(__DIR__ . $_SERVER['SCRIPT_NAME'])) {
    return false; // serve the requested resource as-is.
} else {
    include_once 'index.php';
}