<?php
// Environment
define('DEBUG', true);

// Paths
define('PATH_ROOT', realpath(dirname(__FILE__). '/../'));
define('PATH_APPLICATION', PATH_ROOT . '/application');
define('PATH_DATA', PATH_ROOT . '/data');
define('PATH_VENDOR', PATH_ROOT . '/vendor');
define('PATH_BLUZ', PATH_ROOT . '/vendor/bluzphp/framework/src/Bluz');
define('PATH_PUBLIC', PATH_ROOT . '/public');

// init loader
require_once PATH_BLUZ . '/_loader.php';
require_once PATH_VENDOR . '/autoload.php';

$app = \Application\Tests\BootstrapTest::getInstance();
$app->init('testing');
