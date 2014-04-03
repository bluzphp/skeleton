<?php
/**
 * @author  volkov
 * @created 4/3/14 11:47 AM
 */

echo 'Set permissions' . PHP_EOL;
if (chmod('data/cache', 0777)) {
    echo ' ./data/cache' . PHP_EOL;
}
if (chmod('data/logs', 0777)) {
    echo ' ./data/logs' . PHP_EOL;
}
if (chmod('data/sessions', 0777)) {
    echo ' ./data/session' . PHP_EOL;
}
if (chmod('data/uploads', 0777)) {
    echo ' ./data/uploads' . PHP_EOL;
}
if (chmod('public/uploads', 0777)) {
    echo ' ./public/uploads' . PHP_EOL;
}

echo 'Copy configuration' . PHP_EOL;
if (copy('application/configs/app.dev.sample.php', 'application/configs/app.dev.php')) {
    echo ' ./configs/app.dev.php' . PHP_EOL;
}
if (copy('application/configs/app.testing.sample.php', 'application/configs/app.testing.php')) {
    echo ' ./configs/app.testing.php' . PHP_EOL;
}
if (copy('public/.htaccess.dev.sample', 'public/.htaccess')) {
    echo ' ./public/.htaccess' . PHP_EOL;
}
