<?php
// Environment
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Emulate session
$_COOKIE[session_name()] = uniqid();

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once PATH_APPLICATION . '/Bootstrap.php';
require_once PATH_APPLICATION . '/Exception.php';
