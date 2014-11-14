<?php
/**
 * Generate Swagger configuration
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz\Proxy\Router;
use Swagger\Swagger;

return
/**
 * @return \closure
 */
function ($resource = null) {
    /**
     * @var Bootstrap $this
     */
    $this->useJson();

    $paths = array(
        PATH_APPLICATION . '/models',
        PATH_APPLICATION . '/modules'
    );
    $exclude = array();

    $swagger = new Swagger($paths, $exclude);

    if ($resource) {
        return $swagger->getResource('/'. $resource, ['defaultBasePath' => rtrim(Router::getFullUrl(), '/')]);
    } else {
        return $swagger->getResourceList(['basePath' => '/system/swagger/resource']);
    }
};
