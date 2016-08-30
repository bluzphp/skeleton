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
    if (!is_dir($root . $folder)
        && mkdir($root . $folder, 0750, true)
    ) {
        echo 'Created folder ' . $folder . PHP_EOL;
    }
}

echo shell_exec("setup_permissions 2>&1");

unset($folder);
echo 'Copy .htaccess file' . PHP_EOL;
if (copy($root . '/public/.htaccess.dev.sample', $root . '/public/.htaccess')) {
    echo ' ./public/.htaccess' . PHP_EOL;
}

require_once 'update.php';
