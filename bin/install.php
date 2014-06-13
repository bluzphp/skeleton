<?php
/**
 * @author  volkov
 * @created 4/3/14 11:47 AM
 */

// Root path, one level up
$root = dirname(__DIR__);

echo "Create folders if they don't exist and set permissions" .  PHP_EOL;
$folders = [
    '/data/cache',
    '/data/logs',
    '/data/sessions',
    '/data/uploads',
    '/public/uploads',
];

foreach ($folders as $folder) {
    if (!is_dir($root . $folder)
        && mkdir($root . $folder, 0777, true)) {
        echo 'Created .' . $folder . PHP_EOL;
    }
    if (chmod($root . $folder, 0777)) {
        echo 'Set permissions on .' . $folder . PHP_EOL;
    }

}
unset($folder);
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
