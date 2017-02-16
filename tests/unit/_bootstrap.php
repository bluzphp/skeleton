<?php
// Emulate session
$_SESSION = array();
$_COOKIE[session_name()] = uniqid();
$env = getenv('BLUZ_ENV') ?: 'testing';
$app = \Application\Tests\BootstrapTest::getInstance();
$app->init($env);
