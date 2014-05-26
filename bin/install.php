<?php
/**
 * @author  volkov
 * @created 4/3/14 11:47 AM
 */

// Root path, one level up
$root = dirname(__DIR__);

echo 'Set permissions' . PHP_EOL;
if (chmod($root . '/data/cache', 0777)) {
    echo ' ./data/cache' . PHP_EOL;
}
if (chmod($root . '/data/logs', 0777)) {
    echo ' ./data/logs' . PHP_EOL;
}
if (chmod($root . '/data/sessions', 0777)) {
    echo ' ./data/sessions' . PHP_EOL;
}
if (chmod($root . '/data/uploads', 0777)) {
    echo ' ./data/uploads' . PHP_EOL;
}
if (chmod($root . '/public/uploads', 0777)) {
    echo ' ./public/uploads' . PHP_EOL;
}

echo 'Copy configuration' . PHP_EOL;
if (copy($root . '/application/configs/app.dev.sample.php', $root . '/application/configs/app.dev.php')) {
    echo ' ./configs/app.dev.php' . PHP_EOL;
}
if (copy($root . '/application/configs/app.testing.sample.php', $root . '/application/configs/app.testing.php')) {
    echo ' ./configs/app.testing.php' . PHP_EOL;
}
if (copy($root . '/public/.htaccess.dev.sample', $root . '/public/.htaccess')) {
    echo ' ./public/.htaccess' . PHP_EOL;
}
