<?php
/**
 * @author  volkov
 * @created 4/3/14 11:47 AM
 */

// Root path, one level up
$root = dirname(__DIR__);

echo "Create folders if they don't exist" .  PHP_EOL;
if (!is_dir($root . '/data/cache')
    && mkdir($root . '/data/cache', 0777, true)) {
    echo 'Created ./data/cache' . PHP_EOL;
}
if (!is_dir($root . '/data/logs')
    && mkdir($root . '/data/logs', 0777, true)) {
    echo 'Created ./data/logs' . PHP_EOL;
}
if (!is_dir($root . '/data/sessions')
    && mkdir($root . '/data/sessions', 0777, true)) {
    echo 'Created ./data/sessions' . PHP_EOL;
}
if (!is_dir($root . '/data/uploads')
    && mkdir($root . '/data/uploads', 0777, true)) {
    echo 'Created ./data/uploads' . PHP_EOL;
}
if (!is_dir($root . '/public/uploads')
    && mkdir($root . '/public/uploads', 0777, true)) {
    echo 'Created ./public/uploads' . PHP_EOL;

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
