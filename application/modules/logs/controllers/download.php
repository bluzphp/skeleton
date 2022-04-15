<?php

/**
 * @namespace
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Response;

/**
 * @privilege Management
 *
 * @param $name
 *
 * @return void
 * @throws NotFoundException
 */
return function ($name) {
    /**
     * @var Controller $this
     */
    $file = realpath(PATH_DATA . '/logs/' . $name);

    if (strpos($file, PATH_DATA . '/logs/') !== 0 || !file_exists($file)) {
        throw new NotFoundException();
    }

    Response::addHeader('Content-Description', 'File Transfer');
    Response::addHeader('Expires', '0');
    Response::addHeader('Cache-Control', 'must-revalidate');
    Response::addHeader('Pragma', 'public');

    $this->attachment($file);
};
