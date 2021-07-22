<?php

/**
 * Generate Swagger configuration
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */

namespace Application;

use Bluz\Controller\Controller;
use OpenApi;

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

    $paths = [
        PATH_APPLICATION . '/configs',
        PATH_APPLICATION . '/models',
        PATH_APPLICATION . '/modules',
    ];

    $options = [];

    $openApi = OpenApi\scan($paths, $options);
    print $openApi->toJson();

    // @todo: remove this `die` call
    die;
};
