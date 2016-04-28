<?php
/**
 * Generate Swagger configuration
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Swagger;

return
/**
 * @accept JSON
 * @return \closure
 */
function () {
    /**
     * @var Controller $this
     */
    $this->useJson();

    $paths = array(
        PATH_APPLICATION . '/configs',
        PATH_APPLICATION . '/models',
        PATH_APPLICATION . '/modules',
    );
    $exclude = array();

    return Swagger\scan($paths, $exclude);
};
