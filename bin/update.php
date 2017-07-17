<?php
/**
 * Post Update Composer script
 *
 * @author Anton Shevchuk
 */

// Root path, one level up
$root = dirname(__DIR__);

function copyVendor($mask, $target) {
    global $root;

    foreach (glob($root .'/vendor/'. $mask) as $i => $file) {
        $script = pathinfo($file, PATHINFO_FILENAME);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (is_dir($file)) {
            $newMask = substr($mask, 0, -2) .'/'. $script .'/*';
            $newDir = $root .'/public/'. $target .'/'. $script;
            if (!is_dir($newDir) && @mkdir($newDir)) {
                echo 'Make directory '. $target .'/'. $script .PHP_EOL;
            }
            copyVendor($newMask, $target .'/'.$script);
        } else {
            $newFile = $root .'/public/'. $target .'/'. $script .'.'. $ext;
            // echo "\t". $newFile .PHP_EOL;
            copy($file, $newFile);
        }
    }

    echo "  - Copied $i dir/files to `$target` directory\n";
}

// Copy Swagger-UI to public
copyVendor('public/swagger-ui/dist/*', 'api-reference');

// Copy JavaScript libraries
copyVendor('js/*/*.js', 'js/vendor');

// Copy Twitter Bootstrap
copyVendor('twbs/bootstrap/dist/js/*.js', 'js/vendor');
copyVendor('twbs/bootstrap/dist/css/*', 'css');
copyVendor('twbs/bootstrap/dist/fonts/*', 'fonts');

// Copy Font Awesome
copyVendor('fortawesome/font-awesome/css/*', 'css');
copyVendor('fortawesome/font-awesome/fonts/*', 'fonts');
