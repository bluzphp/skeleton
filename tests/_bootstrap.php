<?php
// This is global bootstrap for autoloading
// Here you can initialize variables that will be available to your tests
// Environment
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Use composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';
