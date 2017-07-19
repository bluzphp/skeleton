<?php
// Emulate session
$_SESSION = array();
$_COOKIE[session_name()] = uniqid('bluz-skeleton-test', false);
