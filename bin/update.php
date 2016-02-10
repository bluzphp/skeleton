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

    foreach (glob($root .'/vendor/'. $mask) as $file) {
        $script = pathinfo($file, PATHINFO_FILENAME);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $newFile = $root .'/public/'. $target .'/'. $script .'.'. $ext;

        echo 'Copy '. $script .' file' . PHP_EOL;
//        echo $file .' >> '. $newFile .PHP_EOL;

        copy($file, $newFile);
    }
}


// Copy JavaScript libraries
copyVendor('js/*/*.js', 'js/vendor');

// Copy Twitter Bootstrap
copyVendor('twbs/bootstrap/dist/js/*.js', 'js/vendor');
copyVendor('twbs/bootstrap/dist/css/*', 'css');
copyVendor('twbs/bootstrap/dist/fonts/*', 'fonts');

// Copy Font Awesome
copyVendor('fortawesome/font-awesome/css/*', 'css');
copyVendor('fortawesome/font-awesome/fonts/*', 'fonts');
