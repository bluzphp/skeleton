<?php
// Environment
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
// Emulate session
$_SESSION = array();
$_COOKIE[session_name()] = uniqid();
require_once dirname(__DIR__) . '/vendor/autoload.php';
$env = getenv('BLUZ_ENV') ?: 'testing';
$app = \Application\Tests\BootstrapTest::getInstance();
$app->init($env);