<?php
/**
 * Post Install Composer script
 *
 * @author  Volkov
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
    if (is_dir($root . $folder)) {
        chmod($root . $folder, 0777);
        echo '  - Updated folder permissions ' . $folder . PHP_EOL;
    } elseif (@mkdir($root . $folder, 0777, true)) {
        echo '  - Created folder ' . $folder . PHP_EOL;
    } else {
        echo '  - ! Can\'t create folder ' . $folder . PHP_EOL;
    }
}
unset($folder);


if (copy($root . '/public/.htaccess.dev.sample', $root . '/public/.htaccess')) {
    echo '  - Copied `.htaccess.dev.sample` to `.htaccess`' . PHP_EOL;
}

require_once 'update.php';

// Docker container
echo shell_exec('setup_permissions 2>&1');
