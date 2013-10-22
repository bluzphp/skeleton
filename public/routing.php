<?php

if (file_exists(__DIR__ . $_SERVER['SCRIPT_NAME'])) {
    return false; // serve the requested resource as-is.
} else {
    include_once 'index.php';
}