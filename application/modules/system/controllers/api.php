<?php
/**
 * Generate Swagger configuration
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Controller\Controller;
use Swagger;

/**
 * @accept HTML
 * @accept JSON
 *
 * @return void
 */
return function () {
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

    // @todo: remove this `die` call
    print Swagger\scan($paths, $exclude);

    die;
};
