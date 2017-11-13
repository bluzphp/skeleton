<?php
// Emulate session
$_SESSION = [];
$_COOKIE[session_name()] = uniqid('bluz-skeleton-test', false);
