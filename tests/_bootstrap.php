<?php
// This is global bootstrap for autoloading
// Here you can initialize variables that will be available to your tests
// Environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Root path - level up
$root = realpath(dirname(__DIR__));

// Use composer autoload
$loader = require $root . '/vendor/autoload.php';
$loader->addPsr4('Application\\Tests\\', __DIR__ .'/src');
$loader->addPsr4('Bluz\\Tests\\', $root . '/vendor/bluzphp/framework/tests/src');
