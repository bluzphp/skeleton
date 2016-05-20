<?php
/**
 * Example of REST controller for GET method
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  19.02.15 16:27
 */
namespace Application;

use Application\Test;
use Bluz\Application\Exception\ForbiddenException;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return Controller
 * @throws ForbiddenException
 * @internal param Controller $this
 */
return
    /**
     * @privilege Read
     * @return mixed
     */
    function () {
        /**
         * @var Controller $this
         */
        Layout::breadCrumbs(
            [
                Layout::ahref('Test', ['test', 'index']),
                'React',
            ]
        );
    };
